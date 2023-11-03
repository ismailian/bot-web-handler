<?php

namespace TeleBot\System\Messages;

use Exception;
use TeleBot\System\BaseHttp;

class Outbound extends BaseHttp
{

    /** @var bool $asJson send response as json */
    protected static bool $asJson = true;

    public static function asJson(): void
    {
        self::$asJson = true;
    }

    /**
     * send response to client
     *
     * @param string|array|object $body
     * @param bool $asJson send as json
     * @return void
     * @throws Exception
     */
    public static function send(string|array|object $body, bool $asJson = false): void
    {
        if (is_array($body) || is_object($body)) {
            if (self::$asJson) {
                $body = json_encode($body, JSON_UNESCAPED_SLASHES);
            } else {
                throw new \Exception('cannot respond with ' . gettype($body) . ' as text/plain');
            }
        }

        if (self::$asJson) {
            self::addHeader('Content-Type', 'application/json');
        }

        die($body);
    }
}