<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Filters;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Enums\InlineChatType;

#[Attribute(Attribute::TARGET_METHOD)]
class Chat implements IEvent
{

    /**
     * default constructor
     *
     * @param InlineChatType $chatType
     */
    public function __construct(public InlineChatType $chatType = InlineChatType::PRIVATE) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        unset($event['update_id']);
        $keys = array_keys($event);
        $chatType = match ($event[$keys[0]]['chat']['type']) {
            'group' => InlineChatType::GROUP,
            'supergroup' => InlineChatType::SUPERGROUP,
            'channel' => InlineChatType::CHANNEL,
            default => InlineChatType::PRIVATE
        };

        return $chatType === $this->chatType;
    }
}