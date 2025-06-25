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

use TeleBot\System\Interfaces\ISessionDriver;

class FileDriver implements ISessionDriver
{

    /** @var string $sessionId session id */
    protected string $sessionId;

    /** @var string $sessionFilePath session file path */
    protected string $sessionFilePath;

    /** @var array $cache cache value of session content */
    protected array $cache = [];

    /**
     * @inheritDoc
     */
    public function __construct(string $sessionId)
    {
        $this->sessionId = $sessionId;
        $this->sessionFilePath = join('/', [
            env('SESSION_DIR', 'session'),
            $this->sessionId . '.json'
        ]);

        if (!file_exists($this->sessionFilePath)) {
            if (!file_exists(dirname($this->sessionFilePath))) {
                @mkdir(dirname($this->sessionFilePath));
            }

            file_put_contents($this->sessionFilePath, json_encode([]));
        }
    }

    /**
     * @inheritDoc
     */
    public function read(): array
    {
        if (empty($this->cache)) {
            $content = file_get_contents($this->sessionFilePath);
            if ($json = json_decode($content, true)) {
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
        return (file_put_contents(
            $this->sessionFilePath,
            json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
        ) > 0);
    }

    /**
     * @inheritDoc
     */
    public function delete(): int|bool
    {
        return @unlink($this->sessionFilePath);
    }
}