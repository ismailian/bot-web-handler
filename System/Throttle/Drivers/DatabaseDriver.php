<?php

namespace TeleBot\System\Throttle\Drivers;

use TeleBot\System\Drivers\DatabaseDriver as Store;
use TeleBot\System\Throttle\RateLimiterDriver;

readonly class DatabaseDriver implements RateLimiterDriver
{
    /** @var Store $store */
    private Store $store;

    public function __construct(private string $table = 'rate_limits')
    {
        $this->store = new Store($this->table, 'cache_key', 'count', 'expires_at', true);
    }

    public function get(string $key): int
    {
        return (int)($this->store->get($key) ?? 0);
    }

    public function increment(string $key, int $ttl): int
    {
        return $this->store->increment($key, $ttl);
    }

    public function ttl(string $key): int
    {
        return $this->store->ttl($key);
    }

    public function reset(string $key): void
    {
        $this->store->delete($key);
    }
}
