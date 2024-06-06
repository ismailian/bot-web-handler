<?php

namespace TeleBot\System\Filesystem;

use TeleBot\System\Router;
use TeleBot\System\Telegram\Parser;
use TeleBot\System\Http\HttpRequest;
use TeleBot\System\Http\HttpResponse;

class Bootstrap
{

    /** @var array $config */
    public static array $config;

    /** @var Router $router */
    protected Router $router;

    /**
     * setup necessary configurations to run the app
     *
     * @return void
     */
    public function setup(): void
    {
        Dotenv::load();
        $this->router = new Router();
        self::$config = require_once 'config.php';

        if ($route = $this->router->matches(self::$config['routes']['web'] ?? [])) {
            if ($handler = Collector::getNamespacedFile($route['handler'])) {
                (new Handler())
                    ->setConfig(self::$config)
                    ->assign(new $handler, explode('::', $route['handler'])[1], array_values($route['params']))
                    ->run();
                return;
            }
        }

        $this->verifyIP()
            ->verifySignature()
            ->verifyRoute()
            ->verifyPayload();

        # blacklisted user or invalid payload
        if (!$this->verifyUserId()) {
            HttpResponse::setStatusCode(200)->end();
        }

        if (!empty(($async = getenv('ASYNC')))) {
            if ($async == 'true') {
                HttpResponse::close();
            }
        }
    }

    /**
     * verify user id
     *
     * @return bool
     */
    private function verifyUserId(): bool
    {
        $payload = HttpRequest::json();
        unset($payload['update_id']);
        $keys = array_keys($payload);
        $userId = $payload[$keys[0]]['from']['id'];

        $whitelist = self::$config['users']['whitelist'];
        $blacklist = self::$config['users']['blacklist'];

        if (!empty($whitelist)) return in_array($userId, $whitelist);
        if (!empty($blacklist)) return !in_array($userId, $blacklist);

        return true;
    }

    /**
     * verify payload
     *
     * @return void
     */
    private function verifyPayload(): void
    {
        $payload = HttpRequest::json();
        if (!isset($payload['update_id']) || empty(array_intersect(Parser::$updates, array_keys($payload)))) {
            HttpResponse::setStatusCode(401)->end();
        }
    }

    /**
     * verify request route
     *
     * @return Bootstrap
     */
    private function verifyRoute(): self
    {
        if (!empty(($routes = self::$config['routes']))) {
            if (!empty($routes['telegram'])) {
                if (!in_array(HttpRequest::uri(), $routes)) {
                    HttpResponse::setStatusCode(401)->end();
                }
            }
        }

        return $this;
    }

    /**
     * verify request signature
     *
     * @return Bootstrap
     */
    private function verifySignature(): self
    {
        if (!empty(($signature = self::$config['signature']))) {
            $value = HttpRequest::headers('X-Telegram-Bot-Api-Secret-Token');
            if (empty($value) || !hash_equals($signature, $value)) {
                HttpResponse::setStatusCode(401)->end();
            }
        }

        return $this;
    }

    /**
     * verify source IP address
     *
     * @return Bootstrap
     */
    private function verifyIP(): self
    {
        if (!empty(($sourceIp = self::$config['ip']))) {
            if (!hash_equals($sourceIp, HttpRequest::ip())) {
                HttpResponse::setStatusCode(401)->end();
            }
        }

        return $this;
    }

}