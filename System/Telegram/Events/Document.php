<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingDocument;

#[Attribute(Attribute::TARGET_METHOD)]
class Document implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingDocument|bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        $isDocument = isset($event[$key]['document']);
        if (!$isDocument) return false;

        return new IncomingDocument($event[$key]['document']);
    }
}