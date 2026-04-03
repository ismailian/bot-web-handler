<?php

namespace TeleBot\System\Drivers;

interface StoreDriver
{
    /**
     * List all keys.
     *
     * @param int $cursor backend cursor (when supported)
     * @param int $count max records to return (when supported)
     * @return array
     */
    public function getAll(int $cursor = 0, int $count = 100): array;

    /**
     * Get value for key.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Persist value for key.
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl TTL in seconds
     * @return bool
     */
    public function set(string $key, mixed $value, ?int $ttl = null): bool;

    /**
     * Delete value by key.
     *
     * @param string $key
     * @return int|bool
     */
    public function delete(string $key): int|bool;

    /**
     * Increment numeric counter.
     *
     * @param string $key
     * @param int $ttl TTL in seconds for new counters
     * @return int
     */
    public function increment(string $key, int $ttl): int;

    /**
     * Remaining TTL in seconds.
     *
     * @param string $key
     * @return int
     */
    public function ttl(string $key): int;
}
