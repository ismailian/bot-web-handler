<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Session\Adapters;

use TeleBot\System\Database\DbClient;
use TeleBot\System\Interfaces\ISessionAdapter;

class DbAdapter implements ISessionAdapter
{

    /** @var DbClient|null $db db client */
    protected static ?DbClient $db = null;

    /** @var string $sessionId session id */
    protected string $sessionId;

    /** @var array $cache cache value of session content */
    protected array $cache = [];

    /**
     * @inheritDoc
     */
    public function __construct(string $sessionId)
    {
        $this->sessionId = $sessionId;
        if (!self::$db) {
            self::$db = new DbClient();
        }

        // create session record (if not exists)
        if (!self::$db->row("SELECT id FROM `sessions` WHERE `session_id` = ? LIMIT 1", [$sessionId])) {
            self::$db->insert("sessions", [
                'session_id' => $sessionId,
                'data' => json_encode([])
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function read(): array
    {
        if (empty($this->cache)) {
            $session = self::$db->row("SELECT data FROM `sessions` WHERE `session_id` = ? LIMIT 1", [$this->sessionId]);
            if ($session && ($json = json_decode($session->data, true))) {
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
        return self::$db->update("sessions",
            ['data' => json_encode($data)],
            ['session_id' => $this->sessionId]
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(): int|bool
    {
        return $this->write([]);
    }
}