<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Cache\Drivers;

use TeleBot\System\Core\Traits\Expirable;
use TeleBot\System\Interfaces\ICacheDriver;

class FileDriver implements ICacheDriver
{

    use Expirable;

    /**
     * @inheritDoc
     */
    public function getAll(int $cursor = 0, int $count = 100): array
    {
        $dir = implode('/', [env('CACHE_DIR'), '*']);
        return array_filter(glob($dir) ?? [], 'is_file');
    }

    /**
     * @inheritDoc
     */
    public function read(string $key): mixed
    {
        $cachePath = env('CACHE_DIR');
        $cacheFilePath = $cachePath . '/' . $key;
        if (!file_exists($cacheFilePath)) {
            return null;
        }

        $content = file_get_contents($cacheFilePath);
        if ($content === false) {
            return null;
        }

        if (($json = json_decode($content, true))) {
            if ($this->isExpired($json)) {
                $this->delete($key);
            }

            $content = $this->restore($json);
        }

        return $content;
    }

    /**
     * @inheritDoc
     */
    public function write(string $key, mixed $data, ?string $ttl = null): bool
    {
        $data = [
            self::TTL_KEY => $ttl ? iso8601_to_timestamp($ttl) : null,
            self::CONTENT_KEY => $data,
        ];

        $cachePath = env('CACHE_DIR');
        $cacheFilePath = $cachePath . '/' . $key;
        return (bool)file_put_contents($cacheFilePath, json_encode($data));
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): bool
    {
        $cachePath = env('CACHE_DIR');
        $cacheFilePath = $cachePath . '/' . $key;
        if (file_exists($cacheFilePath)) {
            return @unlink($cacheFilePath);
        }

        return true;
    }

}