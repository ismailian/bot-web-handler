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
use TeleBot\System\Http\HttpRequest;
use TeleBot\System\Interfaces\ISessionAdapter;
use TeleBot\System\Session\Adapters\FileAdapter;
use TeleBot\System\Session\Adapters\RedisAdapter;

class Session
{

    /** @var ISessionAdapter|null $adapter */
    protected static ?ISessionAdapter $adapter = null;

    /** @var string|mixed $sessionId */
    protected static string $sessionId;

    /**
     * re-start session with custom session id
     *
     * @param string $sessionId
     * @return Session
     */
    public static function withId(string $sessionId): Session
    {
        return self::init($sessionId);
    }

    /**
     * set session data
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function set(string $key, mixed $value): bool
    {
        $data = self::init()::$adapter->read();

        if ($key !== '*') {
            $keys = explode('.', $key);
            $current = &$data;
            foreach ($keys as $key) {
                $current = &$current[$key];
            }

            $current = $value;
            $value = $data;
        }

        return self::$adapter->write($value);
    }

    /**
     * remove session prop
     *
     * @param string $key
     * @return bool
     */
    public static function unset(string $key): bool
    {
        $data = self::init()::$adapter->read();
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

        return self::$adapter->write($data);
    }

    /**
     * initialize session adapter
     *
     * @param string|null $sessionId
     * @return self
     */
    protected static function init(string $sessionId = null): self
    {
        try {
            if (empty(self::$sessionId) || !self::$adapter) {
                if ($sessionId) {
                    self::$sessionId = $sessionId;
                } else {
                    $event = HttpRequest::json();
                    foreach (array_keys($event) as $key) {
                        if ($key !== 'update_id') {
                            self::$sessionId = $event[$key]['from']['id'];
                            break;
                        }
                    }
                }

                self::$adapter = match (getenv('SESSION', true)) {
                    'filesystem' => new FileAdapter(self::$sessionId),
                    'redis' => new RedisAdapter(self::$sessionId),
                };
            }
        } catch (Exception) {}
        return (new self);
    }

    /**
     * get session prop value
     *
     * @param string|null $key
     * @return mixed
     */
    public static function get(string $key = null): mixed
    {
        $data = self::init()::$adapter->read();
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
    public static function destroy(): int|bool
    {
        return self::init()::$adapter->delete();
    }

}
