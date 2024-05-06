<?php

namespace TeleBot\System;

class Process
{

    /**
     * get operating system
     *
     * @return string
     */
    protected static function getOS(): string
    {
        return match (PHP_OS) {
            'WINNT' => 'window',
            'Linux' => 'linux',
        };
    }

    /**
     * execute system command
     *
     * @param mixed ...$args
     * @return string|bool
     */
    public static function run(...$args): string|bool
    {
        $command = join(' ', $args);
        return system($command);
    }

    /**
     * execute system command asynchronously
     *
     * @param mixed ...$args
     * @return void
     */
    public static function runAsync(...$args): void
    {
        $command = join(' ', $args);

        if (self::getOS() == 'linux') {
            $command = "nohup $command > /dev/null 2>&1 &";
            system($command);
        }

        if (self::getOS() == 'windows') {
            $shell = new COM("WScript.Shell");
            $shell->run($command, 0, false);
        }
    }

}