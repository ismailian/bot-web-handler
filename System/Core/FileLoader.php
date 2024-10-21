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

class FileLoader
{

    /**
     * load file(s)
     *
     * @param string $path
     * @return mixed
     */
    public static function load(string $path): mixed
    {
        if (!str_ends_with($path, '*') && file_exists($path)) {
            return require_once $path;
        }

        if (str_ends_with($path, '*')) {
            $phpFiles = glob($path . ".php");
            return array_map(fn($f) => require_once $f, $phpFiles);
        }

        return null;
    }

}