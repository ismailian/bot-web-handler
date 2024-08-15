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

use ReflectionMethod;
use ReflectionException;
use TeleBot\System\Filesystem\Collector;

class Handler
{

    private array $config;

    private mixed $instance;

    private string $method;

    private mixed $args;

    private array $event;

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
    public function assign(mixed $instance, string $method, mixed $args): self
    {
        $this->instance = $instance;
        $this->method = $method;
        $this->args = $args;
        $this->instance->config = $this->config;

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
            [$this->instance, $this->method], [$this->args]
        );
    }

    /**
     * handle fallback
     *
     * @return void
     */
    public function fallback(): void
    {
        if (!empty($this->config['fallback'])) {
            [$class, $method] = explode('::', $this->config['fallback']);
            call_user_func_array(
                [new (Collector::getNamespacedFile($class)), $method], []
            );
        }
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

}