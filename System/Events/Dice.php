<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Types\IncomingDice;

#[Attribute(Attribute::TARGET_METHOD)]
class Dice implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingDice|bool
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $isDice = isset($event['data'][$key]) && isset($event['data'][$key]['dice']);
        if (!$isDice) return false;

        return new IncomingDice($event['data'][$key]['dice']);
    }
}