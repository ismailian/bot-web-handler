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
use TeleBot\System\Session\Drivers\DbDriver;
use TeleBot\System\Interfaces\ISessionDriver;
use TeleBot\System\Session\Drivers\FileDriver;
use TeleBot\System\Session\Drivers\RedisDriver;

class Session
{

    /** @var ISessionDriver|null $adapter */
    protected ?ISessionDriver $adapter = null;

    /** @var string|mixed $sessionId */
    protected string $sessionId;

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
     * @return bool
     */
    public function set(string $key, mixed $value): bool
    {
        $data = $this->init()->adapter->read();

        if ($key !== '*') {
            $keys = explode('.', $key);
            $current = &$data;
            foreach ($keys as $key) {
                $current = &$current[$key];
            }

            $current = $value;
            $value = $data;
        }

        return $this->adapter->write($value);
    }

    /**
     * remove session prop
     *
     * @param string $key
     * @return bool
     */
    public function unset(string $key): bool
    {
        $data = $this->init()->adapter->read();
        $keys = explode('.', $key);
        $temp =& $data;
        foreach ($keys as $key) {
            if (!array_key_exists($key, $temp)) return false;
            $temp =& $temp[$key];
        }

        // unset the target property
        $lastKey = array_pop($keys);
        $temp =& $data;

        foreach ($keys as $key) $temp =& $temp[$key];
        unset($temp[$lastKey]);

        return $this->adapter->write($data);
    }

    /**
     * initialize session adapter
     *
     * @param string|null $sessionId
     * @return self
     */
    protected function init(string $sessionId = null): self
    {
        try {
            if (empty($this->sessionId) || !$this->adapter) {
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

                $this->adapter = match (getenv('SESSION', true)) {
                    'filesystem' => new FileDriver($this->sessionId),
                    'database' => new DbDriver($this->sessionId),
                    'redis' => new RedisDriver($this->sessionId),
                };
            }
        } catch (Exception) {}
        return $this;
    }

    /**
     * get session prop value
     *
     * @param string|null $key
     * @return mixed
     */
    public function get(string $key = null): mixed
    {
        $data = $this->init()->adapter->read();
        if (!$key) return $data;

        $keys = explode('.', $key);
        $lastKey = $keys[count($keys) - 1];
        foreach ($keys as $key) {
            if (isset($data[$key])) {
                if ($key == $lastKey) return $data[$key];
                $data = $data[$key];
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
        return $this->init()->adapter->delete();
    }

}
