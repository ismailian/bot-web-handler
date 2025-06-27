<?php

namespace TeleBot\System\Core\Traits;

use TeleBot\System\Core\Enums\LogType;

trait Loggable
{

    /**
     * Write log content to a single file per day
     *
     * @param LogType $logType
     * @param string $content
     * @return void
     */
    protected static function writeToFile(LogType $logType, string $content): void
    {
        $filename = self::LOG_DIR . '/' . $logType->value . date('_Y_m_d') . '.log';
        $content = date('[H:i:s] ') . $content . PHP_EOL;

        file_put_contents($filename, $content, FILE_APPEND | LOCK_EX);
    }

    /**
     * Write custom info log
     *
     * @param string $content
     * @return void
     */
    public static function info(string $content): void
    {
        self::writeToFile(LogType::INFO, $content);
    }

    /**
     * Write custom error log
     *
     * @param string $content
     * @return void
     */
    public static function error(string $content): void
    {
        self::writeToFile(LogType::ERROR, $content);
    }

}