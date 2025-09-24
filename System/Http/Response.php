<?php /** @noinspection ALL */
/** @noinspection SpellCheckingInspection */

/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Http;

class Response
{

    /** @var array $headers response headers */
    protected static array $headers = [];

    /**
     * set http status code
     *
     * @param int $code
     * @return self
     */
    public function setStatusCode(int $code = 200): self
    {
        http_response_code($code);

        return $this;
    }

    /**
     * send response to client
     *
     * @param string|array|object|null $data
     * @return void
     */
    public function send(string|array|object $data = null): void
    {
        if (!empty($data)) {
            if (is_array($data) || is_object($data)) {
                $data = json_encode($data, JSON_UNESCAPED_SLASHES);
            }
        }

        die($data ?? "");
    }

    /**
     * set downloadable attachment headers
     *
     * @param string $filename
     * @return self
     */
    public function attachment(string $filename): self
    {
        self::addHeader('Content-Type', 'application/octet-stream');
        self::addHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $this;
    }

    /**
     * add http header
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function addHeader(string $key, string $value): self
    {
        self::$headers[strtolower($key)] = $value;
        header("$key: $value");

        return $this;
    }

    /**
     * Get header
     *
     * @param string $key
     * @return string|null
     */
    public function getHeader(string $key): ?string
    {
        return self::$headers[strtolower($key)] ?? null;
    }

    /**
     * send response as json
     *
     * @param array $data
     * @return void
     */
    public function json(array $data): void
    {
        self::addHeader('Content-Type', 'application/json');

        die(json_encode($data));
    }

    /**
     * terminate process
     *
     * @return void
     */
    public function end(): void
    {
        die();
    }

    /**
     * close connection
     *
     * Helpful when you need to send a response without terminating the process
     *
     * @return void
     */
    public function close(): void
    {
        if (is_callable('fastcgi_finish_request')) {
            session_write_close();
            fastcgi_finish_request();
            return;
        }

        ignore_user_abort(true);
        ob_start();

        header('HTTP/1.1 200 OK');
        header('Content-Encoding: none');
        header('Content-Length: ' . ob_get_length());
        header('Connection: close');

        ob_end_flush();
        ob_flush();
        flush();
    }
}