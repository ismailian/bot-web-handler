<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingContact;

#[Attribute(Attribute::TARGET_METHOD)]
class Contact implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingContact|bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        $isContact = isset($event[$key]['contact']);
        if (!$isContact) return false;

        return new IncomingContact($event[$key]['contact']);
    }
}