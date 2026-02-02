<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2026 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Core;

use ArrayAccess;

class ServiceContainer implements ArrayAccess
{

    /** @var array $services service container */
    private static array $services = [];

    /**
     * Initialize a new service
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    private function __initService(string $name, mixed $value): void
    {
        if (is_array($value)) {
            $serviceName = array_shift($value);
            self::$services[$name] = new $serviceName(...$value);
        } else {
            self::$services[$name] = new $value();
        }
    }

    /**
     * Register list of available services
     *
     * @return void
     */
    public function register(): void
    {
        $map = HelperLoader::load('services');
        foreach ($map as $name => $service) {
            $this->__initService($name, $service);
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, self::$services);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return self::$services[$offset] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->__initService($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        unset(self::$services[$offset]);
    }

}
