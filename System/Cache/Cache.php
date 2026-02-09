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

use TeleBot\System\Core\Enums\DataSource;
use TeleBot\System\Interfaces\ICacheDriver;
use TeleBot\System\Cache\Drivers\{FileDriver, RedisDriver};

class Cache
{

    /** @var ICacheDriver|null $client client */
    private ?ICacheDriver $client = null;

    /**
     * initialize driver client
     *
     * @return ICacheDriver
     */
    private function init(): ICacheDriver
    {
        if ($this->client === null) {
            $driver = env('CACHE_DRIVER', DataSource::FILESYSTEM);
            $this->client = match ($driver) {
                DataSource::REDIS => new RedisDriver(),
                DataSource::FILESYSTEM => new FileDriver(),
            };
        }

        return $this->client;
    }

    /**
     * List all available cache data
     *
     * @param int $cursor page number (Redis only)
     * @param int $count max records to return (Redis only)
     * @return array
     */
    public function getAll(int $cursor = 0, int $count = 100): array
    {
        return $this->init()->getAll($cursor, $count);
    }

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
     * Cache data
     *
     * @param string $key cache identifier
     * @param mixed $value data to cache
     * @param string|null $ttl time interval in ISO-8601 format (e.g: PT24H)
     * @return bool
     */
    public function remember(string $key, mixed $value, ?string $ttl = null): bool
    {
        $ttl ??= env('CACHE_TTL');
        return $this->init()->write($key, $value, $ttl);
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