<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Session;

use Exception;
use TeleBot\System\Core\Database;
use TeleBot\System\Core\Enums\DataSource;
use TeleBot\System\Interfaces\ISessionDriver;
use TeleBot\System\Session\Drivers\{DbDriver, FileDriver, RedisDriver};

class Session
{

    /** @var ISessionDriver|null $client */
    protected ?ISessionDriver $client = null;

    /** @var string|mixed $sessionId */
    protected string $sessionId;

    /**
     * initialize session adapter
     *
     * @param string|null $sessionId
     * @return self
     */
    private function init(?string $sessionId = null): self
    {
        try {
            if (empty($this->sessionId) || !$this->client) {
                if ($sessionId) {
                    $this->sessionId = $sessionId;
                } else {
                    $event = request()->json();
                    foreach (array_keys($event) as $key) {
                        if ($key !== 'update_id') {
                            $this->sessionId = $event[$key]['from']['id'];
                            break;
                        }
                    }
                }

                $this->client = match (env('SESSION_DRIVER', DataSource::FILESYSTEM)) {
                    DataSource::REDIS => new RedisDriver($this->sessionId),
                    DataSource::DATABASE => new DbDriver($this->sessionId),
                    DataSource::FILESYSTEM => new FileDriver($this->sessionId),
                };
            }
        } catch (Exception $ex) {
            logger()->onException($ex);
        }

        return $this;
    }

    /**
     * re-start session with custom session id
     *
     * @param string $sessionId
     * @return Session
     */
    public function withId(string $sessionId): Session
    {
        return $this->init($sessionId);
    }

    /**
     * List all available session data
     *
     * @param int $cursor page number (Redis only)
     * @param int $count max records to return (Redis only)
     * @return array
     */
    public function getAll(int $cursor = 0, int $count = 100): array
    {
        return $this->init()->client->getAll($cursor, $count);
    }

    /**
     * set session data
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $ttl time interval in ISO-8601 format (e.g: PT24H) - Set to global session
     * @return bool
     */
    public function set(string $key, mixed $value, ?string $ttl = null): bool
    {
        $data = $this->init()->client->read();
        if ($key !== '*') {
            $keys = explode('.', $key);
            $tmp = &$data;
            foreach ($keys as $key) {
                $tmp = &$tmp[$key];
            }

            $tmp = $value;
            $value = $data;
        }

        return $this->client->write($value, $ttl);
    }

    /**
     * remove session prop
     *
     * @param string $key
     * @return bool
     */
    public function unset(string $key): bool
    {
        $data = $this->init()->client->read();
        if (empty($data)) {
            return false;
        }

        $keys = explode('.', $key);
        $tmp =& $data;
        foreach ($keys as $key) {
            if (!array_key_exists($key, $tmp)) {
                return false;
            }
            $tmp =& $tmp[$key];
        }

        // unset the target property
        $lastKey = array_pop($keys);
        $tmp =& $data;

        foreach ($keys as $key) {
            $tmp =& $tmp[$key];
        }

        unset($tmp[$lastKey]);
        return $this->client->write($data);
    }

    /**
     * get session prop value
     *
     * @param string|null $key
     * @return mixed
     */
    public function get(?string $key = null): mixed
    {
        $data = $this->init()->client->read();
        if (empty($data)) {
            return null;
        }

        if (!$key) {
            return $data;
        }

        $keys = explode('.', $key);
        $lastKey = $keys[count($keys) - 1];
        foreach ($keys as $segmentKey) {
            if (isset($data[$segmentKey])) {
                if ($segmentKey === $lastKey) {
                    return $data[$segmentKey];
                }
                $data = $data[$segmentKey];
            }
        }

        return $data;
    }

    /**
     * destroy session
     *
     * @return int|bool
     */
    public function destroy(): int|bool
    {
        return $this->init()->client->delete();
    }

}
