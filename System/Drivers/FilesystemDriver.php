<?php

namespace TeleBot\System\Drivers;

class FilesystemDriver implements StoreDriver
{
    /** @var string $directory */
    private string $directory;

    /** @var string $extension */
    private string $extension;

    /**
     * @param string $directory storage directory
     * @param string $extension storage file extension
     */
    public function __construct(string $directory, string $extension = '.json')
    {
        $this->directory = rtrim($directory, '/');
        $this->extension = $extension;

        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0755, true);
        }
    }

    /**
     * @inheritDoc
     */
    public function getAll(int $cursor = 0, int $count = 100): array
    {
        $dir = $this->directory . '/*' . $this->extension;
        return array_filter(glob($dir) ?: [], 'is_file');
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        $file = $this->filePath($key);
        if (!file_exists($file)) {
            return null;
        }

        $contents = file_get_contents($file);
        $payload = $contents ? json_decode($contents, true) : null;
        if (!is_array($payload)) {
            return null;
        }

        if (!empty($payload['expires_at']) && time() > (int)$payload['expires_at']) {
            $this->delete($key);
            return null;
        }

        return $payload['value'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, ?int $ttl = null): bool
    {
        $payload = [
            'expires_at' => $ttl ? (time() + $ttl) : null,
            'value' => $value,
        ];

        return (bool)file_put_contents($this->filePath($key), json_encode($payload), LOCK_EX);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): bool
    {
        $file = $this->filePath($key);
        if (file_exists($file)) {
            return @unlink($file);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function increment(string $key, int $ttl): int
    {
        $data = $this->getCounter($key, $ttl);
        $data['count']++;
        $this->set($key, $data, max(1, $data['expires_at'] - time()));
        return $data['count'];
    }

    /**
     * @inheritDoc
     */
    public function ttl(string $key): int
    {
        $payload = $this->payload($key);
        if (!$payload || empty($payload['expires_at'])) {
            return 0;
        }

        return max(0, (int)$payload['expires_at'] - time());
    }

    /**
     * @param string $key
     * @return array|null
     */
    private function payload(string $key): ?array
    {
        $file = $this->filePath($key);
        if (!file_exists($file)) {
            return null;
        }

        $contents = file_get_contents($file);
        $payload = $contents ? json_decode($contents, true) : null;
        if (!is_array($payload)) {
            return null;
        }

        if (!empty($payload['expires_at']) && time() > (int)$payload['expires_at']) {
            $this->delete($key);
            return null;
        }

        return $payload;
    }

    /**
     * @param string $key
     * @param int $ttl
     * @return array
     */
    private function getCounter(string $key, int $ttl): array
    {
        $payload = $this->payload($key);
        $value = $payload['value'] ?? null;

        if (!is_array($value) || !isset($value['count'], $value['expires_at']) || time() > (int)$value['expires_at']) {
            return [
                'count' => 0,
                'expires_at' => time() + $ttl,
            ];
        }

        return $value;
    }

    /**
     * @param string $key
     * @return string
     */
    private function filePath(string $key): string
    {
        return $this->directory . '/' . sha1($key) . $this->extension;
    }
}
