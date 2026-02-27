<?php

namespace TeleBot\System\Throttle\Drivers;

use TeleBot\System\Throttle\RateLimiterDriver;

class FilesystemDriver implements RateLimiterDriver
{

    /** @var string $directory */
    private string $directory;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->directory = env('THROTTLE_DIR');
        $this->directory = rtrim($this->directory, '/');

        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0755, true);
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): int
    {
        $data = $this->read($key);
        if ($data === null || time() > $data['expires_at']) {
            return 0;
        }
        return $data['count'];
    }

    /**
     * @inheritDoc
     */
    public function increment(string $key, int $ttl): int
    {
        $data = $this->read($key);
        $now = time();

        if ($data === null || $now > $data['expires_at']) {
            $data = [
                'count' => 0,
                'expires_at' => $now + $ttl,
            ];
        }

        $data['count']++;
        $this->write($key, $data);

        return $data['count'];
    }

    /**
     * @inheritDoc
     */
    public function ttl(string $key): int
    {
        $data = $this->read($key);
        if ($data === null) {
            return 0;
        }
        return max(0, $data['expires_at'] - time());
    }

    /**
     * @inheritDoc
     */
    public function reset(string $key): void
    {
        $file = $this->filePath($key);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Get the file path
     *
     * @param string $key identity key
     * @return string
     */
    private function filePath(string $key): string
    {
        return $this->directory . '/' . sha1($key) . '.json';
    }

    /**
     * Read data from a file
     *
     * @param string $key identity key
     * @return array|null
     */
    private function read(string $key): ?array
    {
        $file = $this->filePath($key);
        if (!file_exists($file)) {
            return null;
        }
        $contents = file_get_contents($file);
        return $contents ? json_decode($contents, true) : null;
    }

    /**
     * Write data to a file
     *
     * @param string $key identity key
     * @param array $data data to save
     * @return void
     */
    private function write(string $key, array $data): void
    {
        file_put_contents($this->filePath($key), json_encode($data), LOCK_EX);
    }

}