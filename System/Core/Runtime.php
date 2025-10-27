<?php

namespace TeleBot\System\Core;

use TeleBot\System\Core\Enums\RuntimeType;

class Runtime
{

    /** @var RuntimeType $runtimeType property indicating the runtime type */
    protected static RuntimeType $runtimeType;

    /**
     * Initialize runtime checks
     *
     * @param array $config
     * @return void
     */
    public static function init(array $config): void
    {
        $isTelegram = true;

        /** verify routes */
        if (!empty(($routes = $config['routes']))) {
            if (!empty($routes['telegram'])) {
                if (!in_array(request()->uri(), $routes)) {
                    $isTelegram = false;
                }
            }
        }

        /** verify signature */
        if (!empty(($signature = $config['signature']))) {
            $value = request()->header('X-Telegram-Bot-Api-Secret-Token');
            if (empty($value) || !hash_equals($signature, $value)) {
                $isTelegram = false;
            }
        }

        self::$runtimeType = $isTelegram ? RuntimeType::TELEGRAM : RuntimeType::REQUEST;
    }

    /**
     * Get runtime type
     *
     * @return RuntimeType
     */
    public static function getType(): RuntimeType
    {
        return self::$runtimeType;
    }

    /**
     * Set runtime type
     *
     * @param RuntimeType $runtimeType
     * @return void
     */
    public static function setType(RuntimeType $runtimeType): void
    {
        self::$runtimeType = $runtimeType;
    }

    /**
     * Check runtime type
     *
     * @param RuntimeType $runtimeType
     * @return bool
     */
    public static function is(RuntimeType $runtimeType): bool
    {
        return self::$runtimeType === $runtimeType;
    }

    /**
     * Get runtime instance
     *
     * @return Runtime
     */
    public static function getInstance(): Runtime
    {
        return new static;
    }

}