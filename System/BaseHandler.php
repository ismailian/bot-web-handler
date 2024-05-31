<?php

namespace TeleBot\System;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use TeleBot\System\Http\HttpRequest;
use TeleBot\System\Filesystem\Handler;
use TeleBot\System\Filesystem\Collector;
use TeleBot\System\Filesystem\Bootstrap;
use TeleBot\System\Telegram\Filters\Chat;
use TeleBot\System\Telegram\Filters\Only;
use TeleBot\System\Telegram\Filters\Awaits;
use TeleBot\System\Exceptions\InvalidUpdate;
use TeleBot\System\Exceptions\InvalidMessage;

class BaseHandler
{

    /** @var array $config */
    protected array $config = [];

    /** @var array $event */
    protected array $event;

    /** @var object $telegram */
    protected object $telegram;

    /** @var Handler $handler */
    protected Handler $handler;

    /**
     * initialize handler
     *
     * @throws ReflectionException
     * @throws InvalidUpdate|InvalidMessage
     */
    public function init(): bool
    {
        (new Bootstrap())->setup();
        $this->config = Bootstrap::$config;
        $this->event = HttpRequest::event();
        $this->handler = (new Handler())->setConfig($this->config);

        $handlers = Collector::getNamespacedFiles('App/Handlers');
        foreach ($handlers as $handler) {
            $refClass = new ReflectionClass($handler);
            if ($refClass->isSubclassOf(IncomingEvent::class)) {
                foreach ($refClass->getMethods() as $refMethod) {
                    if (!empty($refMethod->getAttributes())) {
                        if ($this->runFilters($refMethod, $this->event) && $this->runEvents($refClass, $refMethod)) {
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
     * @param array $event
     * @return bool
     */
    private function runFilters(ReflectionMethod $method, array $event): bool
    {
        $filters = [
            ...$method->getAttributes(Chat::class),
            ...$method->getAttributes(Only::class),
            ...$method->getAttributes(Awaits::class),
        ];

        foreach ($filters as $filter) {
            if (!($filter->newInstance()->apply($event))) {
                return false;
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
                $eventResult = $attr->newInstance()->apply($this->event);
                if (!$eventResult) return false;
            }
        }

        return (bool)$this->handler->assign(
            $refClass->newInstance(),
            $method->name, $eventResult
        );
    }

}