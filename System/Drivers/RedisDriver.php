<?php

namespace TeleBot\System\Drivers;

use Predis\Client;

class RedisDriver implements StoreDriver
{
    /** @var Client $client */
    private Client $client;

    /** @var string $prefix */
    private string $prefix;

    public function __construct(string $prefix)
    {
        $this->prefix = rtrim($prefix, ':');
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
            [$page, $_keys] = $this->client->scan($cursor, [
                'count' => $count,
                'match' => $this->prefix . ':*',
            ]);

            if (!empty($_keys)) {
                $keys = array_merge($keys, $_keys);
            }

            $cursor = (int)$page;
        } while ((string)$page !== '0');

        return $keys;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        $data = $this->client->get($this->key($key));
        if ($data === null) {
            return null;
        }

        $json = json_decode($data, true);
        return $json === null && $data !== 'null' ? $data : $json;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, ?int $ttl = null): bool
    {
        if (is_array($value) || is_object($value) || $value === null || is_bool($value)) {
            $value = json_encode($value);
        }

        if ($ttl !== null) {
            return (bool)$this->client->set($this->key($key), $value, 'EX', $ttl);
        }

        return (bool)$this->client->set($this->key($key), $value);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): int
    {
        return $this->client->del($this->key($key));
    }

    /**
     * @inheritDoc
     */
    public function increment(string $key, int $ttl): int
    {
        $redisKey = $this->key($key);
        $count = $this->client->incr($redisKey);
        if ($count === 1) {
            $this->client->expire($redisKey, $ttl);
        }

        return (int)$count;
    }

    /**
     * @inheritDoc
     */
    public function ttl(string $key): int
    {
        $ttl = (int)$this->client->ttl($this->key($key));
        return max(0, $ttl);
    }

    private function key(string $key): string
    {
        return $this->prefix . ':' . $key;
    }
}
