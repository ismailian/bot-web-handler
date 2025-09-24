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
    protected Client $client;

    /** @var string $sessionId session id */
    protected string $sessionId;

    /** @var array $cache cache value of session content */
    protected array $cache = [];

    /** @var string $prefix redis key prefix */
    protected string $prefix = 'tg:bots';

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
    public function read(): array
    {
        if (empty($this->cache)) {
            $data = $this->client->get("$this->prefix:{$this->sessionId}");
            if (!empty($data) && ($json = json_decode($data, true))) {
                $this->cache = $json;
            }
        }

        return $this->cache;
    }

    /**
     * @inheritDoc
     */
    public function write(array $data): bool
    {
        $this->cache = $data;
        $result = $this->client->set("$this->prefix:$this->sessionId", json_encode($data));

        return !!$result;
    }

    /**
     * @inheritDoc
     */
    public function delete(): int
    {
        return $this->client->del("$this->prefix:$this->sessionId");
    }
}