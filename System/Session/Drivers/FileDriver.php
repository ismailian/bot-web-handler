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

class FileDriver implements ISessionDriver
{

    use Expirable;

    /** @var string $sessionId session id */
    private string $sessionId;

    /** @var string $sessionFilePath session file path */
    private string $sessionFilePath;

    /** @var array $cached cached session content for quick access */
    private array $cached = [];

    /**
     * @inheritDoc
     */
    public function __construct(string $sessionId)
    {
        $sessDir = env('SESSION_DIR', 'session');
        $sessName = md5($sessionId) . '.json';
        $sessData = [
            self::TTL_KEY => null,
            self::CONTENT_KEY => [],
        ];

        $this->sessionId = $sessionId;
        $this->sessionFilePath = join('/', [$sessDir, $sessName]);
        if (!file_exists($this->sessionFilePath)) {
            if (!file_exists(dirname($this->sessionFilePath))) {
                @mkdir(dirname($this->sessionFilePath));
            }

            file_put_contents($this->sessionFilePath, json_encode($sessData));
        }
    }

    /**
     * @inheritDoc
     */
    public function getAll(int $cursor = 0, int $count = 100): array
    {
        $dir = implode('/', [env('SESSION_DIR'), '*']);
        return array_filter(glob($dir) ?? [], 'is_file');
    }

    /**
     * @inheritDoc
     */
    public function read(): array
    {
        if (empty($this->cached)) {
            $content = file_get_contents($this->sessionFilePath);
            if ($json = json_decode($content, true)) {
                $this->cached = $json;
            }
        }

        if ($this->hasExpired($this->cached)) {
            $this->delete();
            return $this->cached = [];
        }

        return $this->restore($this->cached);
    }

    /**
     * @inheritDoc
     */
    public function write(array $data, ?string $ttl = null): bool
    {
        $data = [
            self::TTL_KEY => $ttl ? iso8601_to_timestamp($ttl) : null,
            self::CONTENT_KEY => $data,
        ];

        $this->cached = $data;
        return (bool)file_put_contents($this->sessionFilePath, json_encode($data));
    }

    /**
     * @inheritDoc
     */
    public function delete(): int|bool
    {
        return @unlink($this->sessionFilePath);
    }
}