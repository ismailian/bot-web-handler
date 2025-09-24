<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use GuzzleHttp\Client;
use TeleBot\System\Cache\Cache;
use TeleBot\System\Session\Session;
use TeleBot\System\Telegram\BotApi;
use TeleBot\System\Telegram\Types\Event;
use TeleBot\System\Http\{Request, Response};
use TeleBot\System\Core\{Database, Logger, Router, Queue, Runtime};

if (!function_exists('router')) {
    /**
     * get router instance
     *
     * @return Router
     */
    function router(): Router
    {
        static $router = null;
        if ($router === null) {
            $router = new Router();
        }
        return $router;
    }
}

if (!function_exists('request')) {
    /**
     * get request instance
     *
     * @return Request
     */
    function request(): Request
    {
        static $request = null;
        if ($request === null) {
            $request = new Request();
        }
        return $request;
    }
}

if (!function_exists('response')) {
    /**
     * get response instance
     *
     * @return Response
     */
    function response(): Response
    {
        static $response = null;
        if ($response === null) {
            $response = new Response();
        }
        return $response;
    }
}

if (!function_exists('bot')) {
    /**
     * get BotApi instance
     *
     * @return BotApi
     */
    function bot(): BotApi
    {
        static $bot = null;
        if ($bot === null) {
            $bot = new BotApi();
        }
        return $bot;
    }
}

if (!function_exists('session')) {
    /**
     * get session instance
     *
     * @return Session
     */
    function session(): Session
    {
        static $session = null;
        if ($session === null) {
            $session = new Session();
        }
        return $session;
    }
}

if (!function_exists('queue')) {
    /**
     * get queue instance
     *
     * @return Queue
     */
    function queue(): Queue
    {
        static $queue = null;
        if ($queue === null) {
            $queue = new Queue();
        }
        return $queue;
    }
}

if (!function_exists('database')) {
    /**
     * get database instance
     *
     * @return Database
     */
    function database(): Database
    {
        static $database = null;
        if ($database === null) {
            $database = new Database();
        }
        return $database;
    }
}

if (!function_exists('cache')) {
    /**
     * get cache instance
     *
     * @return Cache
     */
    function cache(): Cache
    {
        static $cache = null;
        if ($cache === null) {
            $cache = new Cache();
        }
        return $cache;
    }
}

if (!function_exists('http')) {
    /**
     * get http client instance
     *
     * @param array $config
     * @return Client
     */
    function http(array $config = []): Client
    {
        static $http = null;
        if ($http === null) {
            $http = new Client($config);
        }
        return $http;
    }
}

if (!function_exists('event')) {
    /**
     * get telegram event instance
     *
     * @return Event
     * @throws Exception
     */
    function event(): Event
    {
        static $event = null;
        if ($event === null) {
            try {
                $event = new Event(request()->json());
            } catch (Exception $e) {
                throw new Exception("failed to parse incoming event: " . $e->getMessage());
            }
        }
        return $event;
    }
}

if (!function_exists('runtime')) {
    /**
     * get runtime
     *
     * @return Runtime
     */
    function runtime(): Runtime
    {
        static $runtime = null;
        if ($runtime === null) {
            $runtime = Runtime::getInstance();
        }
        return $runtime;
    }
}

if (!function_exists('logger')) {
    /**
     * get logger
     *
     * @return Logger
     */
    function logger(): Logger
    {
        static $logger = null;
        if ($logger === null) {
            $logger = Logger::getInstance();
        }
        return $logger;
    }
}