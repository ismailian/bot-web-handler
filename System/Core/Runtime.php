<?php

namespace TeleBot\System\Core;

use TeleBot\System\Core\Enums\RuntimeType;

class Runtime
{

    /** @var RuntimeType $runtime_type property indicating the runtime type */
    protected static RuntimeType $runtime_type;

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

        self::$runtime_type = $isTelegram ? RuntimeType::TELEGRAM : RuntimeType::REQUEST;
    }

    /**
     * Get runtime type
     *
     * @return RuntimeType
     */
    public static function getType(): RuntimeType
    {
        return self::$runtime_type;
    }

    /**
     * Set runtime type
     *
     * @param RuntimeType $runtime_type
     * @return void
     */
    public static function setType(RuntimeType $runtime_type): void
    {
        self::$runtime_type = $runtime_type;
    }

    /**
     * Check runtime type
     *
     * @param RuntimeType $runtime_type
     * @return bool
     */
    public static function is(RuntimeType $runtime_type): bool
    {
        return self::$runtime_type === $runtime_type;
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