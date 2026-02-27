<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2026 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Throttle;

interface RateLimiterDriver
{
    /**
     * Get the current hit count for a key.
     *
     * @param string $key identity key
     */
    public function get(string $key): int;

    /**
     * Increment the hit count for a key. Returns new count.
     *
     * @param string $key identity key
     * @param int $ttl Time To Live
     */
    public function increment(string $key, int $ttl): int;

    /**
     * Get the TTL (seconds) remaining for a key.
     *
     * @param string $key identity key
     */
    public function ttl(string $key): int;

    /**
     * Reset the hit count for a key.
     */
    public function reset(string $key): void;
}