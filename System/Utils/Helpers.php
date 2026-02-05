<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2025 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use GuzzleHttp\Psr7\Utils;

if (!function_exists('dot')) {
    /**
     * Get value from array or object with dot syntax
     *
     * @param string $path path to the value
     * @param array|object $data data to get value from
     * @return mixed
     */
    function dot(string $path, array|object $data): mixed
    {
        if (empty($data) || empty($path)) {
            return null;
        }

        $segments = explode('.', $path);
        foreach ($segments as $segment) {
            if (is_array($data)) {
                if (!array_key_exists($segment, $data)) {
                    return null;
                }
                $data = $data[$segment];
                continue;
            }

            if (is_object($data)) {
                if (property_exists($data, $segment)) {
                    $data = $data->{$segment};
                    continue;
                }

                $getter = 'get' . ucfirst($segment);
                if (method_exists($data, $getter)) {
                    $data = $data->{$getter}();
                    continue;
                }
                return null;
            }
            return null;
        }

        return $data;
    }
}

if (!function_exists('env')) {
    /**
     * Get value from env
     *
     * @param string $key key for env variable
     * @param string|null $default default value
     * @return mixed|null returns env value, default value or null
     */
    function env(string $key, ?string $default = null): mixed
    {
        $value = getenv($key, true);
        if ($value === false || $value === '') {
            return $default;
        }

        if (in_array($value, ['true', 'false'], true)) {
            $value = $value === 'true';
        }

        return $value;
    }
}

if (!function_exists('isUrl')) {
    /**
     * Check if given input is url
     *
     * @param string $pathOrUrl
     * @return bool
     */
    function isUrl(string $pathOrUrl): bool
    {
        return (bool)filter_var(trim($pathOrUrl), FILTER_VALIDATE_URL);
    }
}

if (!function_exists('getBuffer')) {
    /**
     * Get file buffer
     *
     * @param string $pathOrUrl
     * @return string
     */
    function getBuffer(string $pathOrUrl): string
    {
        return isUrl($pathOrUrl) ? $pathOrUrl : Utils::tryFopen($pathOrUrl, 'r');
    }
}

if (!function_exists('iso8601_to_seconds')) {
    /**
     * Convert ISO-8601 interval to seconds
     *
     * @param string $value time interval in ISO-8601 format
     * @return int|null returns number of seconds or null on error
     */
    function iso8601_to_seconds(string $value): ?int
    {
        try {
            return new \DateTime()->add(new \DateInterval($value))->getTimestamp() - time();
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('iso8601_to_timestamp')) {
    /**
     * Convert ISO-8601 interval to timestamp
     *
     * @param string $value time interval in ISO-8601 format
     * @return int|null returns timestamp or null on error
     */
    function iso8601_to_timestamp(string $value): ?int
    {
        try {
            return new \DateTime()->add(new \DateInterval($value))->getTimestamp();
        } catch (\Exception $e) {
            return null;
        }
    }
}