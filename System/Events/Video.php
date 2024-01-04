<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Types\IncomingVideo;

#[Attribute(Attribute::TARGET_METHOD)]
class Video implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingVideo|bool
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $isVideo = isset($event['data'][$key]) && isset($event['data'][$key]['video']);
        if (!$isVideo) return false;

        return new IncomingVideo($event['data'][$key]['video']);
    }
}