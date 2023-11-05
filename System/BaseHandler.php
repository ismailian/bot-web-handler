<?php

namespace TeleBot\System;

use Exception;
use ReflectionClass;
use ReflectionException;
use JetBrains\PhpStorm\NoReturn;
use TeleBot\System\Filesystem\Handler;
use TeleBot\System\Messages\Inbound;
use TeleBot\System\Filesystem\Collector;

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
     * default constructor
     *
     * @throws ReflectionException
     */
    public function __construct()
    {
        if (!file_exists('config.php')) {
            die('[ERROR] Please run the command: `cp config.php.sample config.php`');
        }

        $this->config = require_once 'config.php';
        $this->event = Inbound::context();
        $this->handler = new Handler();

        $handlers = Collector::getNamespacedFiles('App\Handlers');
        foreach ($handlers as $handler) {
            $refClass = new ReflectionClass($handler);
            foreach ($refClass->getMethods() as $method) {
                foreach ($method->getAttributes($this->event['type']) as $attr) {
                    if ($attr->newInstance()->apply($this->event)) {
                        $this->handler->setConfig($this->config)->assign(
                            $refClass->newInstance($attr),
                            $method->name,
                            $attr->getArguments(),
                            $this->event
                        );
                        break;
                    }
                }
            }
        }
    }

    /**
     * default destructor
     *
     * @throws Exception
     */
    #[NoReturn]
    public function __destruct()
    {
        die();
    }

}