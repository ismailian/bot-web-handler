<?php

namespace TeleBot\System\Messages;

class Inbound
{

    /** @var ?string $_json */
    protected static ?string $_json;

    /** @var ?array $_body */
    protected static ?array $_body;

    /** @var array $event */
    protected array $event;

    /**
     * default constructor
     */
    public function __construct() {}

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
     * return request headers
     *
     * @param string|null $key
     * @return array|string
     */
    public static function headers(string $key = null): array|string
    {
        if (!$key) return getallheaders();
        return getallheaders()[$key];
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
     * get json body
     *
     * @return array
     */
    protected static function json(): array
    {
        return json_decode(file_get_contents('php://input'),true);
    }

    /**
     * get form-data body
     *
     * @return array
     */
    protected static function body(): array
    {
        return self::$_body ?? (self::$_body = $_POST);
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

}