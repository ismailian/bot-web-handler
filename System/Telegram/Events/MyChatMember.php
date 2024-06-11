<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Events;

use Attribute;
use Exception;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingChatMember;

#[Attribute(Attribute::TARGET_METHOD)]
class MyChatMember implements IEvent
{

    /**
     * default constructor
     *
     * @param string|null $status
     */
    public function __construct(protected ?string $status = null) {}

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(array $event): bool
    {
        if (!array_key_exists('my_chat_member', $event)) return false;
        return !$this->status
            || (new IncomingChatMember($event['my_chat_member']))->memberStatus->status == $this->status;
    }
}