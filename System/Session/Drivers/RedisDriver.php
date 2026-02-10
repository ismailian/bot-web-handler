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

use Predis\Client;
use TeleBot\System\Exceptions\MissingToken;
use TeleBot\System\Interfaces\ISessionDriver;

class RedisDriver implements ISessionDriver
{

    /** @var Client $client redis client */
    private Client $client;

    /** @var string $sessionId session id */
    private string $sessionId;

    /** @var array $cached cached session content for quick access */
    private array $cached = [];

    /** @var string $prefix redis key prefix */
    private string $prefix = 'tg:bots';

    /**
     * @inheritDoc
     * @throws MissingToken
     */
    public function __construct(string $sessionId)
    {
        if (empty($token = env('TG_BOT_TOKEN'))) {
            throw new MissingToken;
        }

        $this->prefix = 'tg:bots:' . explode(':', $token)[0];
        $this->sessionId = $sessionId;
        $this->client = new Client([
            'scheme' => 'tcp',
            'host' => env('REDIS_HOST'),
            'port' => env('REDIS_PORT'),
            'user' => env('REDIS_USER'),
            'password' => env('REDIS_PASSWORD')
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getAll(int $cursor = 0, int $count = 100): array
    {
        $keys = [];
        do {
            $options = [
                'count' => $count,
                'match' => $this->prefix . ':*',
            ];

            [$page, $_keys] = $this->client->scan($cursor, $options);
            if (!empty($_keys)) {
                $keys = array_merge($keys, $_keys);
            }
        } while ($page !== '0');
        return $keys;
    }

    /**
     * @inheritDoc
     */
    public function read(): array
    {
        if (empty($this->cached)) {
            $data = $this->client->get("$this->prefix:{$this->sessionId}");
            if (!empty($data) && ($json = json_decode($data, true))) {
                $this->cached = $json;
            }
        }

        return $this->cached;
    }

    /**
     * @inheritDoc
     */
    public function write(array $data, ?string $ttl = null): bool
    {
        $this->cached = $data;
        return !!$this->client->set(
            "$this->prefix:$this->sessionId", json_encode($data),
            ($ttl ? 'EX' : null), ($ttl ? iso8601_to_seconds($ttl) : null)
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(): int
    {
        return $this->client->del("$this->prefix:$this->sessionId");
    }
}