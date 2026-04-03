<?php

namespace TeleBot\System\Throttle\Drivers;

use TeleBot\System\Drivers\FilesystemDriver as Store;
use TeleBot\System\Throttle\RateLimiterDriver;

class FilesystemDriver implements RateLimiterDriver
{
    /** @var Store $store */
    private Store $store;

    public function __construct()
    {
        $this->store = new Store(env('THROTTLE_DIR'));
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
