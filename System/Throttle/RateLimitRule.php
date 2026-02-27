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

/**
 * @property object usage Property holding info about the rate-limit usage
 */
#[\AllowDynamicProperties]
class RateLimitRule
{
    public function __construct(
        /** Maximum number of requests allowed in the window. */
        public int       $maxRequests,
        /** Window size in seconds. */
        public int       $windowSeconds,
        /** Optional: restrict this rule to specific routes (regex or plain string). */
        public ?string   $route = null,
        /**
         * How to build the rate-limit key.
         * Receives the current route string and returns a cache key suffix.
         * Default: use client IP only.
         */
        public ?\Closure $keyResolver = null,
    ) {}
}