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

class Process
{

    /**
     * execute system command
     *
     * The command name is passed through escapeshellcmd() and every argument
     * through escapeshellarg() so that untrusted input cannot inject
     * additional shell commands. Pass the binary as the first argument and
     * each parameter as its own subsequent argument, e.g.
     * Process::run('convert', $input, $output).
     *
     * @param string $command command/binary to execute
     * @param string ...$args arguments to pass to the command
     * @return string|bool
     */
    public static function run(string $command, string ...$args): string|bool
    {
        $cmd = escapeshellcmd($command);
        foreach ($args as $arg) {
            $cmd .= ' ' . escapeshellarg($arg);
        }

        return exec($cmd);
    }

}