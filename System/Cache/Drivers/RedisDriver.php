<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Cache\Drivers;

use TeleBot\System\Drivers\RedisDriver as Store;
use TeleBot\System\Exceptions\MissingToken;
use TeleBot\System\Interfaces\ICacheDriver;

class RedisDriver implements ICacheDriver
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
        $this->store = new Store("tg:bots:{$botId}:cache");
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
