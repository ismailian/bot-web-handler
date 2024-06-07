<?php

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