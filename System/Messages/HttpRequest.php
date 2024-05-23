<?php

namespace TeleBot\System\Messages;

use TeleBot\System\UpdateParser;
use TeleBot\System\Exceptions\InvalidUpdate;
use TeleBot\System\Exceptions\InvalidMessage;

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
     * @return array|string
     */
    public static function headers(string $key = null): array|string
    {
        if (!$key) return getallheaders();
        foreach (getallheaders() as $hkey => $hvalue)
            if (strtolower($hkey) == strtolower($key))
                return $hvalue;

        return '';
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
        return $_SERVER['REQUEST_URI'];
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
        return self::$_query ?? (self::$_query = $_POST);
    }

    /**
     * get Event object
     *
     * @return array
     * @throws InvalidUpdate|InvalidMessage
     */
    public static function event(): array
    {
        if (empty(self::$event)) {
            self::$event = UpdateParser::parseUpdate(self::context());
        }

        return self::$event;
    }

    /**
     * get request data
     *
     * @return array
     */
    public static function context(): array
    {
        return self::hasJson() ? self::json() : self::body();
    }

    /**
     * check for json data
     *
     * @return bool
     */
    protected static function hasJson(): bool
    {
        return (
            strlen(self::$_json = file_get_contents('php://input')) > 0
        );
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