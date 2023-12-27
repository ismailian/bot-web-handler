<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Url implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $isMessage = isset($event['data'][$key]);
        $hasText = isset($event['data'][$key]['text']);
        $hasEntities = !empty($event['data'][$key]['entities']);

        if (!$isMessage || !$hasText || !$hasEntities) return false;
        foreach ($event['data'][$key]['entities'] as $entity)
            if ($entity['type'] == 'url') return true;

        return false;
    }
}