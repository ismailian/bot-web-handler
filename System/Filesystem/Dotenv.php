<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Filesystem;

class Dotenv
{

    /** @var string $envFilename */
    protected static string $envFilename = '.env';

    /**
     * load env file
     *
     * @return void
     */
    public static function load(): void
    {
        if (file_exists(self::$envFilename) && is_file(self::$envFilename)) {
            $envFile = new \SplFileObject(self::$envFilename);
            while (!$envFile->eof()) {
                $validLine = preg_match('/^(?<key>[!a-zA-Z]\S+)=(?<value>.+)?$/', ($line = trim($envFile->fgets())));
                if ($validLine) putenv(str_replace('"', '', $line));
            }
        }
    }

}