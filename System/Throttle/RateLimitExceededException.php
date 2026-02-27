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

use RuntimeException;

class RateLimitExceededException extends RuntimeException
{
    /**
     * Default constructor
     *
     * @param RateLimitResult $result
     */
    public function __construct(public readonly RateLimitResult $result)
    {
        parent::__construct(
            "Rate limit exceeded. Try again in {$result->retryAfter} second(s).",
            429
        );
    }
}