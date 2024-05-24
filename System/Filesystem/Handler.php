<?php

namespace TeleBot\System\Filesystem;

use TeleBot\System\IncomingEvent;

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

        if (is_subclass_of($instance::class, IncomingEvent::class)) {
            $this->instance->config = $this->config;
        }

        return $this;
    }

    /**
     * execute handler
     *
     * @return void
     */
    public function run(): void
    {
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

}