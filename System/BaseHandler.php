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
        $this->event = HttpRequest::event();
        $this->handler = new Handler();
        $this->config = Bootstrap::$config;

        $handlers = Collector::getNamespacedFiles('App/Handlers');
        foreach ($handlers as $handler) {
            $refClass = new ReflectionClass($handler);
            foreach ($refClass->getMethods() as $method) {
                if ($this->runFilters($method, $this->event)) {
                    foreach ($method->getAttributes() as $attr) {
                        if (!str_contains($attr->getName(), 'Filters')) {
                            if (($result = $attr->newInstance()?->apply($this->event))) {
                                $this->handler->setConfig($this->config)->assign(
                                    $refClass->newInstance($attr),
                                    $method->name, $result
                                );
                                return true;
                            }
                        }
                    }
                }
            }
        }

        $this->handler->setConfig($this->config)->fallback();
        return false;
    }

    /**
     * evaluate triggers
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

}