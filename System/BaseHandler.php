<?php

namespace TeleBot\System;

use Exception;
use ReflectionClass;
use ReflectionException;
use JetBrains\PhpStorm\NoReturn;
use TeleBot\System\Exceptions\InvalidMessage;
use TeleBot\System\Messages\Inbound;
use TeleBot\System\Messages\Outbound;
use TeleBot\System\Filesystem\Handler;
use TeleBot\System\Filesystem\Collector;
use TeleBot\System\Filesystem\Bootstrap;
use TeleBot\System\Exceptions\InvalidUpdate;

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
     * @throws ReflectionException
     * @throws InvalidUpdate|InvalidMessage
     */
    public function init(): bool
    {
        (new Bootstrap())->setup();
        $this->event = Inbound::event();
        $this->handler = new Handler();

        $handlers = Collector::getNamespacedFiles('App\Handlers');
        foreach ($handlers as $handler) {
            $refClass = new ReflectionClass($handler);
            foreach ($refClass->getMethods() as $method) {
                foreach ($method->getAttributes() as $attr) {
                    if ($attr->newInstance()?->apply($this->event)) {
                        $this->handler->setConfig($this->config)->assign(
                            $refClass->newInstance($attr),
                            $method->name,
                            $attr->getArguments(),
                            $this->event
                        );
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * default destructor
     *
     * @throws Exception
     */
    #[NoReturn]
    public function __destruct()
    {
        Outbound::end();
    }

}