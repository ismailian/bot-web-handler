<?php

namespace TeleBot\System\Throttle\Drivers;

use TeleBot\System\Drivers\RedisDriver as Store;
use TeleBot\System\Exceptions\MissingToken;
use TeleBot\System\Throttle\RateLimiterDriver;

class RedisDriver implements RateLimiterDriver
{
    /** @var Store $store */
    private Store $store;

    /**
     * @throws MissingToken
     */
    public function __construct()
    {
        if (empty($botToken = env('TG_BOT_TOKEN'))) {
            throw new MissingToken;
        }

        $botId = explode(':', $botToken, 2)[0];
        $this->store = new Store("tg:bots:{$botId}:throttle");
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
