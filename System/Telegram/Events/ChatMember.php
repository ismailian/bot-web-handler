<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use Exception;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingChatMember;

#[Attribute(Attribute::TARGET_METHOD)]
class ChatMember implements IEvent
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
    public function apply(array $event): IncomingChatMember|bool
    {
        if (!array_key_exists("chat_member", $event)) return false;
        return !$this->status
            || (new IncomingChatMember($event['chat_member']))->memberStatus->status == $this->status;
    }
}