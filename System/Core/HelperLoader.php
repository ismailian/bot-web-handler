<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Core;

class HelperLoader
{

    /**
     * load helper files
     *
     * @param mixed $path
     * @return mixed
     */
    public static function load(mixed $path): mixed
    {
        if (is_string($path)) {
            return self::requireFile($path);
        }

        if (is_array($path)) {
            return array_map(fn($f) => self::requireFile($f), $path);
        }

        return null;
    }

    /**
     * require files
     *
     * @param string $filePath
     * @return mixed
     */
    protected static function requireFile(string $filePath): mixed
    {
        if (str_ends_with($filePath, '*')) {
            return array_map(
                fn($f) => require_once $f,
                glob($filePath . ".php")
            );
        } else {
            if (file_exists($filePath)) {
                return require_once $filePath;
            }
        }

        return null;
    }

}