<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingSticker;

#[Attribute(Attribute::TARGET_METHOD)]
class Sticker implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingSticker|bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        return new IncomingSticker($event[$key]['sticker']);
    }
}