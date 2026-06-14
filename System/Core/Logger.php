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

use TeleBot\System\Core\Traits\Loggable;

class Logger
{

    /** @var string LOG_DIR */
    const string LOG_DIR = 'logs';

    use Loggable;

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
    public static function onError(int $code, string $message, ?string $file = null, ?int $line = null, array $context = []): void
    {
        self::log([
            'file' => $file,
            'line' => $line,
            'error' => $message,
            'trace' => $context
        ]);
    }

    /**
     * save a log file
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
                    'ip' => request()->ip(),
                    'uri' => request()->uri(),
                    'method' => request()->method(),
                    'query' => request()->query(),
                    'body' => request()->body(),
                    'json' => request()->json(),
                ]
            ];
        }

        /** create log dir if it does not already exist */
        if (!file_exists('logs') && !is_dir('logs')) {
            mkdir(self::LOG_DIR);
        }

        $logPath = self::LOG_DIR . '/' . date('Y-m-d') . '.log';
        $encodedData = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        if (!$encodedData) {
            unset($data['trace']);
            $encodedData = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }

        /**
         * logs may contain secrets (bot token, DB/Redis credentials),
         * and they must be redacted before writing to disk.
         */
        $encodedData = self::redactSecrets($encodedData);

        // Append so multiple entries within the same day are not lost.
        file_put_contents($logPath, $encodedData . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    /**
     * Redact secrets from log output.
     *
     * Removes Telegram bot tokens wherever they appear and scrubs known
     * secret values pulled from the environment (DB/Redis passwords, tokens).
     *
     * @param string $content
     * @return string
     */
    protected static function redactSecrets(string $content): string
    {
        /** telegram bot tokens: <bot_id>:<secret> (anywhere in the output) */
        $content = preg_replace('/\d{6,}:[A-Za-z0-9_-]{30,}/', '[REDACTED]', $content);

        /** literal secret values sourced from the environment */
        foreach (['TG_BOT_TOKEN', 'DATABASE_PASS', 'REDIS_PASSWORD', 'GITHUB_API_TOKEN'] as $key) {
            $secret = getenv($key);
            if (is_string($secret) && $secret !== '') {
                $content = str_replace($secret, '[REDACTED]', $content);
            }
        }

        return $content;
    }

    /**
     * Strip argument values from a stack trace.
     *
     * Trace frames include call arguments by default, which can leak secrets
     * (e.g. the credentials passed to new PDO(...)). Keep only non-sensitive
     * frame metadata.
     *
     * @param array $trace
     * @return array
     */
    protected static function sanitizeTrace(array $trace): array
    {
        return array_map(static function ($frame) {
            if (is_array($frame)) {
                unset($frame['args']);
            }
            return $frame;
        }, $trace);
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
            'trace' => self::sanitizeTrace($exception->getTrace())
        ]);
    }

    /**
     * Get logger instance
     *
     * @return Logger
     */
    public static function getInstance(): Logger
    {
        return new static;
    }

}