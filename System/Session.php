<?php

namespace TeleBot\System;

use Exception;
use TeleBot\System\Messages\HttpRequest;
use TeleBot\System\Adapters\FileAdapter;
use TeleBot\System\Adapters\RedisAdapter;
use TeleBot\System\Interfaces\ISessionAdapter;

class Session
{

    /** @var ISessionAdapter|null $adapter */
    protected static ?ISessionAdapter $adapter = null;

    /** @var string|mixed $sessionId */
    protected static string $sessionId;

    /**
     * set session data
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function set(string $key, mixed $value): bool
    {
        self::init();

        if ($key !== '*') {
            $data = self::$adapter->read();
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
     * initialize session adapter
     *
     * @return void
     */
    protected static function init(): void
    {
        try {
            if (empty(self::$sessionId) || !self::$adapter) {
                $event = HttpRequest::event()['data'];
                foreach (array_keys($event) as $key) {
                    if ($key !== 'update_id') {
                        self::$sessionId = $event[$key]['from']['id'];
                        break;
                    }
                }

                self::$adapter = match (getenv('SESSION', true)) {
                    'filesystem' => new FileAdapter(self::$sessionId),
                    'redis' => new RedisAdapter(self::$sessionId),
                };
            }
        } catch (Exception) {}
    }

    /**
     * get session prop value
     *
     * @param string|null $key
     * @return mixed
     */
    public static function get(string $key = null): mixed
    {
        self::init();

        $data = self::$adapter->read();
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

}
