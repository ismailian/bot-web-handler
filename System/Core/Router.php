<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Core;

class Router
{

    /**
     * checks if incoming request matches a defined route
     *
     * @param array $routes
     * @return array|bool
     */
    public function matches(array $routes): array|bool
    {
        if (
            empty($routes) || (
                empty($routes[request()->method()])
                && empty($routes[strtoupper(request()->method())])
            )) {
            return false;
        }

        $uri = request()->uri();
        $routes = $routes[request()->method()] ?? $routes[strtoupper(request()->method())];
        foreach ($routes as $route => $handler) {
            if ($this->isDynamic($route)) {
                $routeMeta = $this->getUrlInfo($uri, $route);
                if ($routeMeta['valid']) {
                    $routeMeta['handler'] = $handler;
                    return $routeMeta;
                }
            } else {
                if ($route === $uri) {
                    return [
                        'uri' => $uri,
                        'route' => $route,
                        'handler' => $handler,
                        'params' => [],
                    ];
                }
            }
        }

        return false;
    }

    /**
     * checks if route is dynamic
     *
     * @param string $route
     * @return bool
     */
    protected function isDynamic(string $route): bool
    {
        return (bool)preg_match_all('/(?<key>{[a-z_]+})/', $route);
    }

    /**
     * matches an url to a route.
     * It matches an url against a route and returns data based on what it finds.
     *
     * @param string $url the url from received request.
     * @param string $route the route to compare to.
     * @return array returns an array containing [url, route, params, isMatch].
     */
    protected function getUrlInfo(string $url, string $route): array
    {
        $params = $this->getParams($route);

        /** make the trailing slash optional */
        list($newRoute, $routeLength, $lastSlashPos) = [$route, strlen($route), strrpos($route, '/')];
        if ($routeLength - 1 === $lastSlashPos) $newRoute .= '?';
        else $newRoute .= '/?';

        $regex = @preg_replace(array_keys($params), array_values($params), $newRoute);
        $regex = @str_replace('/', '\/', $regex);
        @preg_match("/^(?<valid>$regex)$/", $url, $data);

        $params = array_filter($data ?? [], function ($key) {
            return !is_numeric($key) && $key !== 'valid';
        }, ARRAY_FILTER_USE_KEY);

        return [
            'url' => $url,
            'route' => $route,
            'params' => $params,
            'valid' => !empty($data['valid'])
        ];
    }

    /**
     * extracts parameters' names from a dynamic route.
     *
     * @param string $route the route to extract parameters from.
     * @return array returns an array with extracted parameters.
     */
    protected function getParams(string $route): array
    {
        @preg_match_all('/\/?(?<name>\{[^0-9\s\-\/]+})\/?/', $route, $result);
        $params = [];
        foreach ($result['name'] as $param) {
            $params["/\\$param/"] = '(?<' . @trim($param, '{}') . '>[^\s/]+)';
        }

        return $params;
    }

}