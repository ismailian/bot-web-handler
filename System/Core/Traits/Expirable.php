<?php

namespace TeleBot\System\Core\Traits;

trait Expirable
{

    /** @var string $expireKey key pointing to the data expiration timestamp */
    protected string $expireKey = 'expires';

    /**
     * Add expire timestamp to the data
     *
     * @param mixed $value data
     * @param string|null $expires relative time
     * @return void
     */
    protected function addExpireTimestamp(mixed &$value, ?string $expires = null): void
    {
        if (!$expires) {
            return;
        }

        $timestamp = strtotime($expires);
        if ($timestamp > time()) {
            if (!is_array($value)) {
                $value = ['converted' => true, 'value' => $value];
            }
            $value[$this->expireKey] = $timestamp;
        }
    }

    /**
     * Check if data is expired
     *
     * @param array $data
     * @return bool
     */
    protected function isExpired(array $data): bool
    {
        return !empty($data[$this->expireKey]) && $data[$this->expireKey] < time();
    }

    /**
     * Restore data to its original type
     *
     * @param array $data
     * @return mixed
     */
    protected function restore(array $data): mixed
    {
        if (array_key_exists('converted', $data) && array_key_exists('value', $data)) {
            $data = $data['value'];
        }

        return $data;
    }

}