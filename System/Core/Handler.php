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
use ReflectionMethod;
use ReflectionException;
use TeleBot\System\IncomingEvent;
use TeleBot\System\Telegram\Types\Event;
use TeleBot\System\Core\Attributes\Delegate;

class Handler
{

    private array $config;

    private mixed $instance;

    private string $method;

    private mixed $args;

    /**
     * set bot configurations
     *
     * @param mixed $config
     * @return self
     */
    public function setConfig(mixed $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * assign current handler
     *
     * @param mixed $instance
     * @param string $method
     * @param mixed $args
     * @return $this
     */
    public function assign(mixed $instance, string $method, mixed $args = null): self
    {
        $this->instance = $instance;
        $this->method = $method;
        $this->args = $args;
        $this->instance->config = $this->config;

        if (!empty($this->args) && !is_array($this->args)) {
            $this->args = [$this->args];
        }

        return $this;
    }

    /**
     * execute handler
     *
     * @return void
     * @throws ReflectionException
     */
    public function run(): void
    {
        $this->executeDelegates();

        call_user_func_array(
            [$this->instance, $this->method],
            $this->args ?? []
        );
    }

    /**
     * execute any available delegates
     *
     * @return void
     * @throws ReflectionException
     */
    private function executeDelegates(): void
    {
        $refMethod = new ReflectionMethod($this->instance, $this->method);
        $delegates = $refMethod->getAttributes(
            Delegate::class
        );

        foreach ($delegates as $delegate) {
            $delegate->newInstance()();
        }
    }

    /**
     * handle fallback
     *
     * @return void
     * @throws Exception
     */
    public function fallback(): void
    {
        if (!empty($this->config['fallback'])) {
            $fallback = $this->config['fallback'];

            /** callable */
            if (is_callable($fallback)) {
                $fallback(new Event(request()->json()));
                return;
            }

            /** invokable class */
            if (is_subclass_of($fallback, IncomingEvent::class)) {
                $fallback();
                return;
            }

            /** handler */
            [$class, $method] = explode('::', $fallback);
            call_user_func_array(
                [new (Filesystem::getNamespacedFile($class)), $method], []
            );
        }
    }

}