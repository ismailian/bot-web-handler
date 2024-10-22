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

use TeleBot\System\Core\Traits\Cacheable;
use TeleBot\System\Interfaces\ICacheDriver;

class FileDriver implements ICacheDriver
{

    use Cacheable;

    /**
     * @inheritDoc
     */
    public function read(string $key): mixed
    {
        $cachePath = getenv('CACHE_DIR', true);
        $cacheFilePath = $cachePath . "/" . $key;
        if (!file_exists($cacheFilePath)) {
            return null;
        }

        $cacheFileContent = file_get_contents($cacheFilePath);
        if ($cacheFileContent === false) {
            return null;
        }

        if (($json = json_decode($cacheFileContent, true))) {
            return $json;
        }

        return $cacheFileContent;
    }

    /**
     * @inheritDoc
     */
    public function write(string $key, mixed $data): bool
    {
        $cachePath = getenv('CACHE_DIR', true);
        $cacheFilePath = $cachePath . "/" . $key;
        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }

        return (bool)file_put_contents($cacheFilePath, $data);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): bool
    {
        $cachePath = getenv('CACHE_DIR', true);
        $cacheFilePath = $cachePath . "/" . $key;
        if (file_exists($cacheFilePath)) {
            return @unlink($cacheFilePath);
        }

        return true;
    }

}