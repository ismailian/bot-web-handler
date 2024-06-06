<?php

namespace TeleBot\System\Http;

class HttpRequest
{

    /** @var array|null $_query */
    protected static ?array $_query;

    /** @var ?string $_json */
    protected static ?string $_json;

    /** @var ?array $_body */
    protected static ?array $_body;

    /** @var ?array $event */
    protected static ?array $event;

    /**
     * return request headers
     *
     * @param string|null $key
     * @return array|string|null
     */
    public static function headers(string $key = null): array|string|null
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
    public static function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * get
     * @return string
     */
    public static function uri(): string
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
    public static function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * get query parameters
     *
     * @return array
     */
    public static function query(): array
    {
        return self::$_query ?? (self::$_query = $_GET);
    }

    /**
     * get json body
     *
     * @return array
     */
    public static function json(): array
    {
        if (($json = json_decode(file_get_contents('php://input'), true)))
            return $json;

        return [];
    }

    /**
     * get form-data body
     *
     * @return array
     */
    public static function body(): array
    {
        $body = self::$_body ?? (self::$_body = $_POST);
        return [
            ...$body,
            ...self::json()
        ];
    }

}