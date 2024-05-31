<?php

namespace TeleBot\System\Filesystem;

class Dotenv
{

    /** @var string $envFilename */
    protected static string $envFilename = '.env';

    /**
     * load env file
     *
     * @return void
     */
    public static function load(): void
    {
        if (file_exists(self::$envFilename) && is_file(self::$envFilename)) {
            $envFile = new \SplFileObject(self::$envFilename);
            while (!$envFile->eof()) {
                $validLine = preg_match('/^(?<key>[!a-zA-Z]\S+)=(?<value>.+)?$/', ($line = trim($envFile->fgets())));
                if ($validLine) putenv(str_replace('"', '', $line));
            }
        }
    }

}