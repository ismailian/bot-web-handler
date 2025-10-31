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
        if ($value === false) {
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