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

use TeleBot\System\Core\Traits\Expirable;
use TeleBot\System\Interfaces\ISessionDriver;

class DbDriver implements ISessionDriver
{

    use Expirable;

    /** @var string sessions table */
    private const string SESSION_TABLE = 'sessions';

    /** @var string primary key for row */
    private const string SESSION_ID_KEY = 'session_id';

    /** @var string key for session data */
    private const string SESSION_DATA_KEY = 'data';

    /** @var string $sessionId session id */
    private string $sessionId;

    /** @var array $cached cached session content for quick access */
    private array $cached = [];

    /**
     * @inheritDoc
     */
    public function __construct(string $sessionId)
    {
        $this->sessionId = $sessionId;
        if (!database()->row("SELECT id FROM `sessions` WHERE `session_id` = ?", [$sessionId])) {
            database()->insert(self::SESSION_TABLE, [
                self::SESSION_ID_KEY => $sessionId,
                self::SESSION_DATA_KEY => json_encode([])
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function getAll(int $cursor = 0, int $count = 100): array
    {
        return database()->rows('SELECT * FROM ' . self::SESSION_TABLE) ?? [];
    }

    /**
     * @inheritDoc
     */
    public function read(): array
    {
        if (empty($this->cached)) {
            $session = database()->row("SELECT ttl,data FROM `sessions` WHERE `session_id` = ?", [$this->sessionId]);
            if (!empty($session)) {
                $this->cached[self::SESSION_DATA_KEY] = $session[self::SESSION_DATA_KEY] ?? [];
                $this->cached[self::TTL_KEY] = $session[self::TTL_KEY] ?? null;
            }
        }

        if (!empty($this->cached[self::TTL_KEY]) && (int)$this->cached[self::TTL_KEY] < time()) {
            database()->delete(self::SESSION_TABLE, [
                self::SESSION_ID_KEY => $this->sessionId,
            ]);

            return $this->cached = [];
        }

        $data = $this->cached[self::SESSION_DATA_KEY] ?? [];
        if (!empty($data) && !is_array($data) && ($json = json_decode($data, true)) !== null) {
            $data = $json;
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function write(array $data, ?string $ttl = null): bool
    {
        $sessData = [
            self::SESSION_DATA_KEY => json_encode($data),
            self::TTL_KEY => $ttl ? iso8601_to_timestamp($ttl) : null,
        ];

        $this->cached = $sessData;
        return database()->update(self::SESSION_TABLE, $sessData,
            [self::SESSION_ID_KEY => $this->sessionId]
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(): int|bool
    {
        return database()->delete(self::SESSION_TABLE, [
            self::SESSION_ID_KEY => $this->sessionId
        ]);
    }
}