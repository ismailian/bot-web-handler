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
     * @param mixed $path path or pattern to the php file
     * @param bool $once whether to require once or not
     * @return mixed
     */
    public static function load(mixed $path, bool $once = true): mixed
    {
        if (is_string($path)) {
            return self::requireFile(
                self::appendExtension($path), $once
            );
        }

        if (is_array($path)) {
            return array_map(
                fn($f) => self::requireFile(
                    self::appendExtension($f), $once
                ), $path
            );
        }

        return [];
    }

    /**
     * require files
     *
     * @param string $filePath
     * @param bool $once
     * @return mixed
     */
    protected static function requireFile(string $filePath, bool $once): mixed
    {
        /**
         * Require php file
         *
         * @param $f
         * @return mixed
         */
        $require = function ($f) use ($once) {
            if (!file_exists($f)) {
                return null;
            }
            if ($once) {
                return require_once $f;
            }
            return require $f;
        };

        if (str_ends_with($filePath, '*')) {
            return array_map(fn($f) => $require($f),
                glob($filePath . ".php")
            );
        }

        return $require($filePath);
    }

    /**
     * Add php extension to end of filename
     *
     * @param string $path filename
     * @return string
     */
    private static function appendExtension(string $path): string
    {
        if (str_ends_with($path, '*') || str_ends_with($path, '.php')) {
            return $path;
        }

        return $path . '.php';
    }

}