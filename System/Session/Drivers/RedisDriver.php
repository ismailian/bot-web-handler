<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Session\Drivers;

use TeleBot\System\Drivers\RedisDriver as Store;
use TeleBot\System\Exceptions\MissingToken;
use TeleBot\System\Interfaces\ISessionDriver;

class RedisDriver implements ISessionDriver
{
    /** @var Store $store */
    private Store $store;

    /** @var string $key */
    private string $key;

    /** @var array $cached */
    private array $cached = [];

    /**
     * @throws MissingToken
     */
    public function __construct(string $sessionId)
    {
        if (empty($botToken = env('TG_BOT_TOKEN'))) {
            throw new MissingToken;
        }

        $botId = explode(':', $botToken, 2)[0];
        $this->store = new Store("tg:bots:{$botId}:session");
        $this->key = $sessionId;
    }

    public function getAll(int $cursor = 0, int $count = 100): array
    {
        return $this->store->getAll($cursor, $count);
    }

    public function read(): array
    {
        if (empty($this->cached)) {
            $value = $this->store->get($this->key);
            if (is_array($value)) {
                $this->cached = $value;
            }
        }

        return $this->cached;
    }

    public function write(array $data, ?string $ttl = null): bool
    {
        $this->cached = $data;
        $ttl = $ttl ? iso8601_to_seconds($ttl) : null;
        return $this->store->set($this->key, $data, $ttl);
    }

    public function delete(): int|bool
    {
        $this->cached = [];
        return $this->store->delete($this->key);
    }
}
