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

use Exception;
use TeleBot\System\Core\Attributes\Middleware;
use TeleBot\System\Telegram\Filters\{Only, Chat, Awaits};
use ReflectionClass, ReflectionMethod, ReflectionException;

class Handler
{

    /** @var mixed $instance handler object */
    private static mixed $instance;

    /** @var string $method handler method */
    private static string $method;

    /** @var array|null $args list of args to pass to the handler */
    private static mixed $args = null;

    /**
     * assign context handler
     *
     * @param mixed $instance handler object
     * @param string $method handler method
     * @param mixed $args handler args
     * @return void
     */
    public static function assign(mixed $instance, string $method, mixed $args = null): void
    {
        self::$instance = $instance;
        self::$method = $method;
        self::$args = $args;

        if (!empty(self::$args) && !is_array(self::$args)) {
            self::$args = [self::$args];
        }
    }

    /**
     * execute handler
     *
     * @return void
     * @throws ReflectionException
     */
    public static function run(): void
    {
        self::invokeMiddlewares();

        call_user_func_array([self::$instance, self::$method], self::$args ?? []);
    }

    /**
     * evaluate filters
     *
     * @param ReflectionMethod $method
     * @return bool
     */
    private static function invokeFilters(ReflectionMethod $method): bool
    {
        $filters = [
            ...$method->getAttributes(Chat::class),
            ...$method->getAttributes(Only::class),
            ...$method->getAttributes(Awaits::class),
            ...$method->getAttributes(Middleware::class),
        ];

        foreach ($filters as $filter) {
            if (is_subclass_of($method->class, Middleware::class)) {
                $filter->newInstance()();
            } else {
                if (!($filter->newInstance()->apply(request()->json()))) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * evaluate events
     *
     * @param ReflectionClass $refClass
     * @param ReflectionMethod $method
     * @return bool
     * @throws ReflectionException
     */
    private static function invokeEvents(ReflectionClass $refClass, ReflectionMethod $method): bool
    {
        $eventResult = null;
        foreach ($method->getAttributes() as $attr) {
            if (!str_contains($attr->getName(), 'Filters')) {
                $eventResult = $attr->newInstance()->apply(request()->json());
                if (!$eventResult) {
                    return false;
                }
            }
        }

        self::assign($refClass->newInstance(), $method->name, $eventResult);
        return true;
    }

    /**
     * invoke any attached middlewares
     *
     * @return void
     * @throws ReflectionException
     */
    private static function invokeMiddlewares(): void
    {
        $refMethod = new ReflectionMethod(self::$instance, self::$method);
        $middlewares = $refMethod->getAttributes(Middleware::class);

        foreach ($middlewares as $middleware) {
            $middleware->newInstance()();
        }
    }

    /**
     * handle fallback
     *
     * @return void
     * @throws Exception
     */
    public static function fallback(): void
    {
        $fallback = config('fallback');
        if (empty($fallback)) {
            return;
        }

        if (is_callable($fallback)) {
            $fallback();
        } elseif (is_string($fallback) && class_exists($fallback)) {
            [$class, $method] = explode('::', $fallback, 2);
            call_user_func_array(
                [new (Filesystem::getNamespacedFile($class)), $method], []
            );
        }
    }

    /**
     * Check if handler matches and can be run
     *
     * @param ReflectionClass $refClass reflection class
     * @param ReflectionMethod $refMethod reflection method
     * @return bool
     */
    public static function check(ReflectionClass $refClass, ReflectionMethod $refMethod): bool
    {
        try {
            return self::invokeFilters($refMethod) && self::invokeEvents($refClass, $refMethod);
        } catch (ReflectionException $e) {
            return false;
        }
    }
}