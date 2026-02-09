<?php

namespace TeleBot\System\Core\Traits;

trait Expirable
{

    /** @var string key pointing to the data expiration timestamp */
    public const string TTL_KEY = 'ttl';

    /** @var string key pointing to the content */
    public const string CONTENT_KEY = 'content';

    /**
     * Check if data is expired
     *
     * @param array|null $data
     * @return bool
     */
    protected function hasExpired(?array $data = null): bool
    {
        return !empty($data) && !empty($data[self::TTL_KEY]) && $data[self::TTL_KEY] < time();
    }

    /**
     * Restore data to its original value
     *
     * @param mixed $data cached data
     * @return mixed original content
     */
    protected function restore(mixed $data): mixed
    {
        if (empty($data)
            || !is_array($data)
            || !array_key_exists(self::TTL_KEY, $data)
            || !array_key_exists(self::CONTENT_KEY, $data)) {
            return $data;
        }

        return $data[self::CONTENT_KEY];
    }

}