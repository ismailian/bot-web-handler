<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Cache;

use TeleBot\System\Core\Traits\Cacheable;
use TeleBot\System\Interfaces\ICacheDriver;
use TeleBot\System\Cache\Drivers\{FileDriver, RedisDriver};

class Cache
{

    use Cacheable;

    /** @var ICacheDriver|null $client client */
    protected ?ICacheDriver $client = null;

    /**
     * get cached data
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->init()->read($key);
    }

    /**
     * initialize driver client
     *
     * @return ICacheDriver
     */
    protected function init(): ICacheDriver
    {
        if ($this->client === null) {
            $driver = env('CACHE_DRIVER');
            $this->client = match ($driver) {
                'redis' => new RedisDriver(),
                'filesystem' => new FileDriver(),
            };
        }

        return $this->client;
    }

    /**
     * set data
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function remember(string $key, mixed $value): bool
    {
        return $this->init()->write($key, $value);
    }

    /**
     * clear cache
     *
     * @param string $key
     * @return bool
     */
    public function forget(string $key): bool
    {
        return $this->init()->delete($key);
    }

}