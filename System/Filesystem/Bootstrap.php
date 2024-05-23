<?php

namespace TeleBot\System\Filesystem;

use TeleBot\System\Router;
use TeleBot\System\UpdateParser;
use TeleBot\System\Messages\HttpRequest;
use TeleBot\System\Messages\HttpResponse;

class Bootstrap
{

    /** @var array $config */
    public static array $config;

    /** @var string $envFilename */
    protected string $envFilename = '.env';

    /** @var Router $router */
    protected Router $router;

    /**
     * setup necessary configurations to run the app
     *
     * @return void
     */
    public function setup(): void
    {
        $this->__env();
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

        $allowedIp = $this->verifyIP();
        $validSignature = $this->verifySignature();
        $allowedRoute = $this->verifyRoute();
        $validPayload = $this->verifyPayload();
        $validUser = $this->verifyUserId();

        # unauthorized source
        if (!$allowedIp || !$validSignature || !$allowedRoute) {
            HttpResponse::setStatusCode(401)->end();
        }
        
        # blacklisted user or invalid payload
        if (!$validPayload || !$validUser) {
            HttpResponse::setStatusCode(200)->end();
        }

        if (!empty(($async = getenv('ASYNC')))) {
            if ($async == 'true') {
                HttpResponse::close();
            }
        }
    }

    /**
     * load env configurations
     *
     * @return void
     */
    protected function __env(): void
    {
        if (file_exists($this->envFilename) && is_file($this->envFilename)) {
            $envFile = new \SplFileObject($this->envFilename);
            while (!$envFile->eof()) {
                $validLine = preg_match('/^(?<key>[!a-zA-Z]\S+)=(?<value>.+)?$/', ($line = trim($envFile->fgets())));
                if ($validLine) putenv(str_replace('"', '', $line));
            }
        }
    }

    /**
     * verify source IP address
     *
     * @return bool
     */
    private function verifyIP(): bool
    {
        if (!empty(($sourceIp = self::$config['ip']))) {
            return hash_equals($sourceIp, HttpRequest::ip());
        }

        return true;
    }

    /**
     * verify request signature
     *
     * @return bool
     */
    private function verifySignature(): bool
    {
        if (!empty(($signature = self::$config['signature']))) {
            return hash_equals($signature, HttpRequest::headers('X-Telegram-Bot-Api-Secret-Token'));
        }

        return true;
    }

    /**
     * verify request route
     *
     * @return bool
     */
    private function verifyRoute(): bool
    {
        if (!empty(($routes = self::$config['routes']))) {
            if (!empty($routes['telegram'])) {
                return in_array(HttpRequest::uri(), $routes);
            }
        }

        return true;
    }

    /**
     * verify payload
     *
     * @return bool
     */
    private function verifyPayload(): bool
    {
        $payload = HttpRequest::context();
        if (isset($payload['update_id'])) {
            if (!empty(array_intersect(UpdateParser::$updates, array_keys($payload)))) {
                return true;
            }
        }

        return false;
    }

    /**
     * verify user id
     *
     * @return bool
     */
    private function verifyUserId(): bool
    {
        $payload = HttpRequest::context();
        unset($payload['update_id']);
        $keys = array_keys($payload);
        $userId = $payload[$keys[0]]['from']['id'];

        $whitelist = self::$config['users']['whitelist'];
        $blacklist = self::$config['users']['blacklist'];

        if (!empty($whitelist)) return in_array($userId, $whitelist);
        if (!empty($blacklist)) return !in_array($userId, $blacklist);

        return true;
    }

}