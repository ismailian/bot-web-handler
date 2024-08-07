<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System;

use TeleBot\System\Http\HttpRequest;

class ExceptionHandler
{

    /**
     * handle runtime errors
     *
     * @param int $code
     * @param string $message
     * @param string|null $file
     * @param int|null $line
     * @param array $context
     * @return void
     */
    public static function onError(int $code, string $message, string $file = null, int $line = null, array $context = []): void
    {
        self::log([
            'file' => $file,
            'line' => $line,
            'error' => $message,
            'trace' => $context
        ]);
    }

    /**
     * save log file
     *
     * @param array $data
     * @return void
     */
    protected static function log(array $data): void
    {
        if (php_sapi_name() !== 'cli') {
            $data = [
                ...$data,
                'request' => [
                    'ip' => HttpRequest::ip(),
                    'uri' => HttpRequest::uri(),
                    'method' => HttpRequest::method(),
                    'query' => HttpRequest::query(),
                    'body' => HttpRequest::body(),
                ]
            ];
        }

        /** create log dir if it does not already exist */
        if (!file_exists('logs') && !is_dir('logs')) {
            mkdir('logs');
        }

        $logPath = 'logs/' . date('Y-m-d__H-i-s') . '.log';
        $encodedData = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        if (!$encodedData) {
            unset($data['trace']);
            $encodedData = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }

        file_put_contents($logPath, $encodedData);
    }

    /**
     * handle runtime exceptions
     *
     * @param $exception
     * @return void
     */
    public static function onException($exception): void
    {
        self::log([
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'error' => $exception->getMessage(),
            'trace' => $exception->getTrace()
        ]);
    }

}