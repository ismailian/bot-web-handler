<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Text implements IEvent
{

    /**
     * default constructor
     *
     * @param bool $textOnly capture plain-text only
     * this will only capture text messages without mentions, urls or commands
     */
    public function __construct(public bool $textOnly = false) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $isMessage = isset($event['data'][$key]);
        $hasText = isset($event['data'][$key]['text']);
        if (!$isMessage || !$hasText) return false;

        return !$this->textOnly || !count(array_filter(
            $event['data'][$key]['entities'] ?? [],
            fn($entity) => in_array($entity['type'], ['bot_command', 'url', 'mention'])
        ));
    }
}