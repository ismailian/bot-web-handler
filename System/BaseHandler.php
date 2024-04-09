<?php

namespace TeleBot\System;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use TeleBot\System\Filters\Awaits;
use TeleBot\System\Messages\Inbound;
use TeleBot\System\Filesystem\Handler;
use TeleBot\System\Filesystem\Collector;
use TeleBot\System\Filesystem\Bootstrap;
use TeleBot\System\Exceptions\InvalidUpdate;
use TeleBot\System\Exceptions\InvalidMessage;
use TeleBot\System\Filters\Only;

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
        $this->event = Inbound::event();
        $this->handler = new Handler();

        $handlers = Collector::getNamespacedFiles('App/Handlers');
        foreach ($handlers as $handler) {
            $refClass = new ReflectionClass($handler);
            foreach ($refClass->getMethods() as $method) {
                if ($this->runTriggers($method, $this->event)) {
                    foreach ($method->getAttributes() as $attr) {
                        if (!str_ends_with($attr->getName(), 'Awaits')) {
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

        return false;
    }

    /**
     * evaluate triggers
     *
     * @param ReflectionMethod $method
     * @param array $event
     * @return bool
     */
    private function runTriggers(ReflectionMethod $method, array $event): bool
    {
        $triggers = [
            ...$method->getAttributes(Only::class),
            ...$method->getAttributes(Awaits::class),
        ];

        foreach ($triggers as $trigger) {
            if (!($trigger->newInstance()->apply($event))) {
                return false;
            }
        }

        return true;
    }

}