<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Interfaces\IValidator;

#[Attribute(Attribute::TARGET_METHOD)]
class Text implements IEvent
{

    /**
     * default constructor
     *
     * @param bool $cleanText capture plain-text only
     * this will only capture text messages without mentions, urls or commands
     */
    public function __construct(public bool $cleanText = false, public ?IValidator $Validator = null) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        if (!array_key_exists($key, $event)) return false;
        if (!array_key_exists('text', $event[$key])) return false;

        $isCleanText = !$this->cleanText || !count(array_filter(
                $event[$key]['entities'] ?? [],
                fn($entity) => in_array($entity['type'], ['bot_command', 'url', 'mention'])
            ));

        return $isCleanText && (!$this->Validator || $this->Validator->isValid($event[$key]['text']));
    }
}