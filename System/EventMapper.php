<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use TeleBot\System\Core\Handler;
use TeleBot\System\Core\Delegate;
use TeleBot\System\Core\Bootstrap;
use TeleBot\System\Http\HttpRequest;
use TeleBot\System\Filesystem\Collector;
use TeleBot\System\Telegram\Filters\Chat;
use TeleBot\System\Telegram\Filters\Only;
use TeleBot\System\Telegram\Filters\Awaits;

class EventMapper
{

    /** @var Handler $handler */
    protected Handler $handler;

    /**
     * initialize handler
     *
     * @throws ReflectionException
     */
    public function init(): bool
    {
        (new Bootstrap())->setup();
        $this->handler = (new Handler())->setConfig(Bootstrap::$config);

        $handlers = Collector::getNamespacedFiles('App/Handlers');
        foreach ($handlers as $handler) {
            $refClass = new ReflectionClass($handler);
            if ($refClass->isSubclassOf(IncomingEvent::class)) {
                foreach ($refClass->getMethods() as $refMethod) {
                    if (!empty($refMethod->getAttributes())) {
                        if ($this->runFilters($refMethod) && $this->runEvents($refClass, $refMethod)) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * evaluate filters
     *
     * @param ReflectionMethod $method
     * @return bool
     */
    private function runFilters(ReflectionMethod $method): bool
    {
        $filters = [
            ...$method->getAttributes(Delegate::class),
            ...$method->getAttributes(Chat::class),
            ...$method->getAttributes(Only::class),
            ...$method->getAttributes(Awaits::class),
        ];

        foreach ($filters as $filter) {
            if (is_subclass_of($method->class, Delegate::class)) {
                $filter->newInstance()();
            } else {
                if (!($filter->newInstance()->apply(HttpRequest::json()))) {
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
    private function runEvents(ReflectionClass $refClass, ReflectionMethod $method): bool
    {
        $eventResult = null;
        foreach ($method->getAttributes() as $attr) {
            if (!str_contains($attr->getName(), 'Filters')) {
                $eventResult = $attr->newInstance()->apply(HttpRequest::json());
                if (!$eventResult) return false;
            }
        }

        return (bool)$this->handler->assign(
            $refClass->newInstance(),
            $method->name, $eventResult
        );
    }

}