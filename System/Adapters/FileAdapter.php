<?php

namespace TeleBot\System\Adapters;

use TeleBot\System\Interfaces\ISessionAdapter;

class FileAdapter implements ISessionAdapter
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
            getenv('SESSION_DIR', true),
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
}