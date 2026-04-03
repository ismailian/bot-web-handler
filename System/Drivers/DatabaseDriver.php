<?php

namespace TeleBot\System\Drivers;

readonly class DatabaseDriver implements StoreDriver
{
    public function __construct(
        private string $table,
        private string $keyColumn = 'cache_key',
        private string $valueColumn = 'value',
        private string $ttlColumn = 'expires_at',
        private bool $createTable = false,
    ) {
        if ($this->createTable) {
            $this->ensureTable();
        }
    }

    /**
     * @inheritDoc
     */
    public function getAll(int $cursor = 0, int $count = 100): array
    {
        return database()->rows("SELECT * FROM {$this->table} LIMIT {$count}") ?: [];
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        $row = $this->row($key);
        if (!$row) {
            return null;
        }

        if (!empty($row[$this->ttlColumn]) && (int)$row[$this->ttlColumn] <= time()) {
            $this->delete($key);
            return null;
        }

        return $this->decode($row[$this->valueColumn] ?? null);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, ?int $ttl = null): bool
    {
        $expiresAt = $ttl ? time() + $ttl : null;
        $value = $this->encode($value);

        $stmt = database()->run(
            "INSERT INTO {$this->table} ({$this->keyColumn}, {$this->valueColumn}, {$this->ttlColumn})
             VALUES (:key, :value, :ttl)
             ON DUPLICATE KEY UPDATE
               {$this->valueColumn} = VALUES({$this->valueColumn}),
               {$this->ttlColumn} = VALUES({$this->ttlColumn})",
            [
                'key' => $key,
                'value' => $value,
                'ttl' => $expiresAt,
            ]
        );

        return (bool)$stmt;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): int|bool
    {
        return database()->delete($this->table, [$this->keyColumn => $key]);
    }

    /**
     * @inheritDoc
     */
    public function increment(string $key, int $ttl): int
    {
        $now = time();
        $expiresAt = $now + $ttl;

        database()->run(
            "INSERT INTO {$this->table} ({$this->keyColumn}, {$this->valueColumn}, {$this->ttlColumn})
             VALUES (:key, :value1, :ttl1)
             ON DUPLICATE KEY UPDATE
               {$this->valueColumn} = IF({$this->ttlColumn} <= :now, :value2, CAST({$this->valueColumn} AS UNSIGNED) + 1),
               {$this->ttlColumn} = IF({$this->ttlColumn} <= :now, :ttl2, {$this->ttlColumn})",
            [
                'key' => $key,
                'value1' => 1,
                'value2' => 1,
                'ttl1' => $expiresAt,
                'ttl2' => $expiresAt,
                'now' => $now,
            ]
        );

        return (int)($this->get($key) ?? 0);
    }

    /**
     * @inheritDoc
     */
    public function ttl(string $key): int
    {
        $row = $this->row($key);
        if (!$row || empty($row[$this->ttlColumn])) {
            return 0;
        }

        return max(0, (int)$row[$this->ttlColumn] - time());
    }

    private function row(string $key): array|object|bool
    {
        return database()->row(
            "SELECT {$this->valueColumn}, {$this->ttlColumn} FROM {$this->table} WHERE {$this->keyColumn} = :key",
            ['key' => $key]
        );
    }

    private function encode(mixed $value): string|int|float|null
    {
        if (is_array($value) || is_object($value) || is_bool($value) || $value === null) {
            return json_encode($value);
        }

        return $value;
    }

    private function decode(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        $json = json_decode($value, true);
        return $json === null && $value !== 'null' ? $value : $json;
    }

    private function ensureTable(): void
    {
        database()->getClient()->exec(
            "CREATE TABLE IF NOT EXISTS `{$this->table}` (
                `{$this->keyColumn}` VARCHAR(255) NOT NULL,
                `{$this->valueColumn}` TEXT NULL,
                `{$this->ttlColumn}` INT NULL,
                `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`{$this->keyColumn}`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );
    }
}
