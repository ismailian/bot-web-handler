<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Http;

class Request
{

    /** @var array $headers request headers */
    protected array $_headers = [];

    /** @var ?array $_json */
    protected ?array $_json;

    public function __construct()
    {
        $this->_headers = getallheaders();
        $this->_json = json_decode(file_get_contents('php://input'), true) ?? [];
    }

    /**
     * Get header
     *
     * @param string $key
     * @return string|null
     */
    public function header(string $key): ?string
    {
        return $this->headers()[strtolower($key)] ?? null;
    }

    /**
     * return request headers
     *
     * @return array|string|null
     */
    public function headers(): array|string|null
    {
        return $this->_headers;
    }

    /**
     * get source IP address
     *
     * @return string
     */
    public function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * get host name
     *
     * @return string|null
     */
    public function origin(): ?string
    {
        return $_SERVER['HTTP_ORIGIN'] ?? null;
    }

    /**
     * get
     * @return string
     */
    public function uri(): string
    {
        if (array_key_exists('REDIRECT_URL', $_SERVER)) {
            return $_SERVER['REDIRECT_URL'];
        }

        /**
         * most likely nginx
         * must remove the query string from the uri
         */
        $requestUri = $_SERVER['REQUEST_URI'];
        if (str_contains($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, strpos($requestUri, '?'));
        }

        return $requestUri;
    }

    /**
     * get request method (lowercase)
     *
     * @return string
     */
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Check if request method matches the provided one
     *
     * @param string $method
     * @return bool
     */
    public function isMethod(string $method): bool
    {
        return $this->method() === strtolower($method);
    }

    /**
     * get query parameters
     *
     * @param string|null $key
     * @param bool $raw
     * @return string|array|null
     */
    public function query(?string $key = null, bool $raw = false): string|array|null
    {
        if ($raw) {
            return $_SERVER['QUERY_STRING'] ?? '';
        }

        if ($key !== null) {
            return $_GET[$key] ?? null;
        }

        return $_GET;
    }

    /**
     * get form-data body
     *
     * @param string|null $key
     * @param bool $raw
     * @return array|string
     */
    public function body(?string $key = null, bool $raw = false): mixed
    {
        if ($raw) {
            return file_get_contents('php://input');
        }

        if ($key !== null) {
            return $_POST[$key] ?? null;
        }

        return $_POST;
    }

    /**
     * get full json or single value
     *
     * @param string|null $key
     * @param bool $raw
     * @return array
     */
    public function json(?string $key = null, bool $raw = false): mixed
    {
        if ($key !== null) {
            return $this->_json[$key] ?? null;
        }

        if ($raw) {
            return file_get_contents('php://input');
        }

        return $this->_json;
    }

}