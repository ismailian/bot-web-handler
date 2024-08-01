<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Traits;

trait Messageable
{

    /** @var array|string[] $updates list of message updates */
    protected array $updates = [
        'message', 'edited_message',
        'channel_post', 'edited_channel_post',
        'business_message', 'edited_business_message',
    ];

    /**
     * checks if a message update exists
     *
     * @param array $keys
     * @param string|null $strict
     * @return bool
     */
    public function isMessage(array $keys, string $strict = null): bool
    {
        if (!is_null($strict)) {
            return
                !empty(array_intersect($this->updates, $keys))
                && $this->first($keys) == $strict;
        }

        return !empty(array_intersect($this->updates, $keys));
    }

    /**
     * get the first update key that exists in the provided list
     *
     * @param array $keys
     * @return string|null
     */
    public function first(array $keys): ?string
    {
        $result = array_intersect($this->updates, $keys);
        if (empty($result)) return null;

        return array_values($result)[0];
    }

}