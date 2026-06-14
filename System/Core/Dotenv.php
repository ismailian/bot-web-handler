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

use SplFileObject;

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
            $envFile = new SplFileObject(self::$envFilename);
            while (!$envFile->eof()) {
                $line = trim($envFile->fgets());
                if ($line === '' || $line[0] === '#') {
                    continue;
                }

                if (!preg_match('/^(?<key>[!a-zA-Z]\S+)=(?<value>.*)$/', $line, $m)) {
                    continue;
                }

                $key = $m['key'];
                $value = trim($m['value']);

                // Strip a single pair of surrounding quotes (preserving inner ones).
                if (strlen($value) >= 2
                    && ($value[0] === '"' || $value[0] === "'")
                    && $value[-1] === $value[0]) {
                    $value = substr($value, 1, -1);
                }

                putenv("$key=$value");
            }
        }
    }

}