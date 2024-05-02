<?php

namespace TeleBot\System\Adapters;

use Predis\Client;
use TeleBot\System\Exceptions\MissingToken;
use TeleBot\System\Interfaces\ISessionAdapter;

class RedisAdapter implements ISessionAdapter
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
        if (empty($token = getenv('TG_BOT_TOKEN', true))) {
            throw new MissingToken;
        }

        $this->prefix = 'tg:bots:' . explode(':', $token)[0];
        $this->sessionId = $sessionId;
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
    public function read(): array
    {
        if (empty($this->cache)) {
            if (($json = json_decode($this->client->get("{$this->prefix}:{$this->sessionId}"), true))) {
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
        $result = $this->client->set("{$this->prefix}:{$this->sessionId}", json_encode($data));

        return !!$result;
    }
}