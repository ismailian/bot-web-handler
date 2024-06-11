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
        return system(join(' ', $args));
    }

}