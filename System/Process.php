<?php

namespace TeleBot\System;

class Process
{

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

        if (PHP_OS == 'Linux') {
            $command = "nohup $command > /dev/null 2>&1 &";
            system($command);
        }

        if (PHP_OS == 'WINNT') {
            $shell = new COM("WScript.Shell");
            $shell->run($command, 0, false);
        }
    }

}