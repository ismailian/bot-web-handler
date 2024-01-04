<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Types\IncomingPhoto;

#[Attribute(Attribute::TARGET_METHOD)]
class Photo implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): array
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $photo = new IncomingPhoto($event['data'][$key]['photo']);

        return [
            isset($event['data'][$key]) && isset($event['data'][$key]['photo']),
            $photo
        ];
    }
}