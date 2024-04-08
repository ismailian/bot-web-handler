<?php

namespace TeleBot\System;

use TeleBot\System\Messages\Inbound;

class Router
{

    /**
     * default constructor
     */
    public function __construct() {}

    /**
     * checks if incoming request matches a defined route
     *
     * @param array $routes
     * @return array|bool
     */
    public function matches(array $routes): array|bool
    {
        if (empty($routes) || empty($list = $routes[Inbound::method()])) {
            return false;
        }

        foreach ($list as $route => $handler) {
            /**
             * todo: match dynamic routes
             * e.g: /users/{id}/profile
             */
            if ($route === Inbound::uri()) {
                return [
                    'uri' => Inbound::uri(),
                    'route' => $route,
                    'handler' => $handler,
                    'params' => [],
                ];
            }
        }

        return false;
    }

}