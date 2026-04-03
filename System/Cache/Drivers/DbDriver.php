<?php

namespace TeleBot\System\Cache\Drivers;

use TeleBot\System\Drivers\DatabaseDriver as Store;
use TeleBot\System\Interfaces\ICacheDriver;

class DbDriver implements ICacheDriver
{
    /** @var Store $store */
    private Store $store;

    public function __construct()
    {
        $this->store = new Store('cache', 'cache_key', 'cache_value', 'ttl', true);
    }

    public function getAll(int $cursor = 0, int $count = 100): array
    {
        return $this->store->getAll($cursor, $count);
    }

    public function read(string $key): mixed
    {
        return $this->store->get($key);
    }

    public function write(string $key, mixed $data, ?string $ttl = null): bool
    {
        $ttl = $ttl ? iso8601_to_seconds($ttl) : null;
        return $this->store->set($key, $data, $ttl);
    }

    public function delete(string $key): bool
    {
        return (bool)$this->store->delete($key);
    }
}
