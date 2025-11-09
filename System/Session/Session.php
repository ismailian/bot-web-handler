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
use TeleBot\System\Interfaces\ISessionDriver;
use TeleBot\System\Session\Drivers\{
    DbDriver,
    FileDriver,
    RedisDriver,
};

class Session
{

    /** @var ISessionDriver|null $client */
    protected ?ISessionDriver $client = null;

    /** @var string|mixed $sessionId */
    protected string $sessionId;

    /** @var string $expireKey key pointing to the session expiration timestamp */
    protected string $expireKey = 'expires';

    /**
     * initialize session adapter
     *
     * @param string|null $sessionId
     * @return self
     */
    protected function init(?string $sessionId = null): self
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

                $this->client = match (env('SESSION', 'filesystem')) {
                    'filesystem' => new FileDriver($this->sessionId),
                    'database' => new DbDriver($this->sessionId),
                    'redis' => new RedisDriver($this->sessionId),
                };
            }
        } catch (Exception) {
        }
        return $this;
    }

    /**
     * Add expire timestamp to the session data
     *
     * @param mixed $value session data
     * @param string|null $expires relative time
     * @return void
     */
    protected function addExpireTimestamp(mixed &$value, ?string $expires = null): void
    {
        if (!$expires) {
            return;
        }

        $timestamp = strtotime($expires);
        if ($timestamp > time()) {
            if (!is_array($value)) {
                $value = ['converted' => true, 'value' => $value];
            }
            $value[$this->expireKey] = $timestamp;
        }
    }

    /**
     * Check if session data is expired
     *
     * @param array $data
     * @return bool
     */
    protected function isExpired(array $data): bool
    {
        return !empty($data[$this->expireKey]) && $data[$this->expireKey] < time();
    }

    /**
     * Restore session data to its original type
     *
     * @param array $data
     * @return mixed
     */
    protected function restore(array $data): mixed
    {
        if (array_key_exists('converted', $data) && array_key_exists('value', $data)) {
            $data = $data['value'];
        }

        return $data;
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
     * set session data
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $expires
     * @return bool
     */
    public function set(string $key, mixed $value, ?string $expires = null): bool
    {
        $data = $this->init()->client->read();

        if ($key !== '*') {
            $keys = explode('.', $key);
            $tmp = &$data;
            foreach ($keys as $key) {
                $tmp = &$tmp[$key];
            }

            $this->addExpireTimestamp($value, $expires);
            $tmp = $value;
            $value = $data;
        } else {
            $this->addExpireTimestamp($value, $expires);
        }

        return $this->client->write($value);
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
        if (!$key) {
            if ($this->isExpired($data)) {
                $this->destroy();
                return null;
            }

            unset($data[$this->expireKey]);
            return $data;
        }

        $keys = explode('.', $key);
        $lastKey = $keys[count($keys) - 1];
        foreach ($keys as $segmentKey) {
            if (isset($data[$segmentKey])) {
                if ($segmentKey == $lastKey) {
                    if ($this->isExpired($data[$segmentKey])) {
                        $this->unset($key);
                        return null;
                    }

                    unset($data[$segmentKey][$this->expireKey]);
                    return $this->restore($data[$segmentKey]);
                }
                $data = $data[$segmentKey];
            }
        }

        return null;
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
