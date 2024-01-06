<?php

namespace TeleBot\System;

use TeleBot\System\Messages\Inbound;

class SessionManager
{

    /** @var string|mixed $sessionId */
    protected static string $sessionId;

    /** @var array in-memory session */
    protected static array $cached;

    /**
     * initialize session
     *
     * @return SessionManager
     */
    public static function start(): SessionManager
    {
        if (empty(self::$sessionId)) {
            try {
                self::$sessionId = Inbound::event()['data']['message']['from']['id'];
                if (!file_exists('session') && !is_dir('session')) {
                    mkdir('session');
                }
            } catch (\Exception) {}
        }

        $sessionKey = 'session/' . self::$sessionId . ' .json';
        $session = (new self);
        if (file_exists($sessionKey)) {
            $session::$cached = json_decode(file_get_contents($sessionKey), true);
            return $session;
        }

        $session::$cached = ['state' => 'started'];
        file_put_contents($sessionKey, json_encode($session::$cached, JSON_UNESCAPED_SLASHES));
        return $session;
    }

    /**
     * get session key value
     *
     * @param string|null $key
     * @return mixed
     */
    public static function get(string $key = null): mixed
    {
        if (empty(self::$cached)) self::start();
        if (!empty($key) && !array_key_exists($key, self::$cached)) return null;

        return $key ? self::$cached[$key] : self::$cached;
    }

    /**
     * set session data
     *
     * @param string $state
     * @param array $data
     * @return void
     */
    public static function set(string $state, array $data): void
    {
        if (empty(self::$sessionId) || empty(self::$cached)) {
            self::start();
        }

        $sessionKey = 'session/' . self::$sessionId . ' .json';
        self::$cached = ['state' => $state, ...$data];
        file_put_contents($sessionKey, json_encode(self::$cached, JSON_UNESCAPED_SLASHES));
    }

}