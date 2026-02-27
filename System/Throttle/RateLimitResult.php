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

readonly class RateLimitResult
{
    public function __construct(
        /** Whether the request is allowed. */
        public bool $allowed,
        /** Maximum requests permitted in the window. */
        public int  $limit,
        /** Remaining requests before the window is exhausted. */
        public int  $remaining,
        /** Unix timestamp when the current window resets. */
        public int  $resetAt,
        /** Seconds until the window resets. */
        public int  $retryAfter,
    ) {}

    /**
     * Emit standard rate-limit HTTP response headers.
     */
    public function applyHeaders(): void
    {
        response()->addHeader('X-RateLimit-Limit', $this->limit);
        response()->addHeader('X-RateLimit-Remaining', $this->remaining);
        response()->addHeader('X-RateLimit-Reset', $this->resetAt);

        if (!$this->allowed) {
            response()->addHeader('Retry-After', $this->retryAfter);
            response()->setStatusCode(429);
        }
    }
}