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

    /** @var array|null $_query */
    protected ?array $_query;

    /** @var ?string $_json */
    protected ?string $_json;

    /** @var ?array $_body */
    protected ?array $_body;

    /** @var ?array $event */
    protected ?array $event;

    /**
     * return request headers
     *
     * @param string|null $key
     * @return array|string|null
     */
    public function headers(string $key = null): array|string|null
    {
        if (!$key) return getallheaders();
        foreach (getallheaders() as $k => $v) {
            if (strtolower($k) == strtolower($key)) {
                return $v;
            }
        }

        return null;
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
     * get query parameters
     *
     * @param string|null $key
     * @return string|array|null
     */
    public function query(string $key = null): string|array|null
    {
        $this->_query = $_GET;
        if (!is_null($key)) {
            return $this->_query[$key] ?? null;
        }

        return $this->_query;
    }

    /**
     * get form-data body
     *
     * @param bool $raw
     * @return array|string
     */
    public function body(bool $raw = false): array|string
    {
        if ($raw) {
            return file_get_contents('php://input');
        }

        $body = $this->_body ?? ($this->_body = $_POST);
        return [
            ...$body,
            ...$this->json()
        ];
    }

    /**
     * get json body
     *
     * @return array
     */
    public function json(): array
    {
        if (($json = json_decode(file_get_contents('php://input'), true))) {
            return $json;
        }

        return [];
    }

}