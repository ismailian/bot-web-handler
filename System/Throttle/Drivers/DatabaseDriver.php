<?php

namespace TeleBot\System\Throttle\Drivers;

use TeleBot\System\Throttle\RateLimiterDriver;

readonly class DatabaseDriver implements RateLimiterDriver
{

    /**
     * Default constructor
     *
     * @param string $table
     */
    public function __construct(private string $table = 'rate_limits')
    {
        $this->ensureTable();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): int
    {
        return database()->count(
            "SELECT count FROM {$this->table} WHERE cache_key = :key AND expires_at > :exp",
            [
                'key' => $key,
                'exp' => time()
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function increment(string $key, int $ttl): int
    {
        $expiresAt = time() + $ttl;
        database()->run(
            "INSERT INTO {$this->table} (cache_key, count, expires_at)
             VALUES (:key, 1, :exp1)
             ON DUPLICATE KEY UPDATE
               count      = IF(expires_at <= UNIX_TIMESTAMP(), 1, count + 1),
               expires_at = IF(expires_at <= UNIX_TIMESTAMP(), :exp2, expires_at)",
            [
                'key' => $key,
                'exp1' => $expiresAt,
                'exp2' => $expiresAt,
            ]
        );

        return $this->get($key);
    }

    /**
     * @inheritDoc
     */
    public function ttl(string $key): int
    {
        $result = database()->row(
            "SELECT expires_at FROM {$this->table} WHERE cache_key = :key",
            ['key' => $key]
        );

        if (!$result) {
            return 0;
        }

        return max(0, (int)$result['expires_at'] - time());
    }

    /**
     * @inheritDoc
     */
    public function reset(string $key): void
    {
        database()->delete($this->table, ['cache_key' => $key]);
    }

    /**
     * Create database table
     *
     * @return void
     */
    private function ensureTable(): void
    {
        database()->getClient()->exec(
            "CREATE TABLE IF NOT EXISTS `{$this->table}` (
                `cache_key`  VARCHAR(255) NOT NULL,
                `count`      INT          NOT NULL DEFAULT 1,
                `expires_at` INT          NOT NULL,
                `updated_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`cache_key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );
    }
}