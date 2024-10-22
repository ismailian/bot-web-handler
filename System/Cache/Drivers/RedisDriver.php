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

use Predis\Client;
use TeleBot\System\Exceptions\MissingToken;
use TeleBot\System\Interfaces\ICacheDriver;

class RedisDriver implements ICacheDriver
{

    /** @var Client $client redis client */
    protected Client $client;

    /** @var mixed $cache cache value of cache content */
    protected mixed $cache = [];

    /** @var string $prefix redis key prefix */
    protected string $prefix = 'tg:bots';

    /**
     * default constructor
     *
     * @throws MissingToken
     */
    public function __construct()
    {
        if (empty($token = getenv('TG_BOT_TOKEN', true))) {
            throw new MissingToken;
        }

        $token = explode(':', $token)[0];
        $this->prefix = "tg:bots:$token:cache";
        $this->client = new Client([
            'scheme' => 'tcp',
            'host' => getenv('REDIS_HOST', true),
            'port' => getenv('REDIS_PORT', true),
            'user' => getenv('REDIS_USER', true),
            'password' => getenv('REDIS_PASSWORD', true)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function read(string $key): mixed
    {
        if (empty($this->cache)) {
            $data = $this->client->get("{$this->prefix}:{$key}");
            if (!empty($data) && ($json = json_decode($data, true))) {
                $this->cache = $json;
            }
        }

        return $this->cache;
    }

    /**
     * @inheritDoc
     */
    public function write(string $key, mixed $data): bool
    {
        $this->cache = $data;
        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }

        $result = $this->client->set("{$this->prefix}:{$key}", $data);

        return !!$result;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): bool
    {
        return $this->client->del("{$this->prefix}:{$key}");
    }
}