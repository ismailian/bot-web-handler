<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Text implements IEvent
{

    /**
     * default constructor
     */
    public function __construct(public bool $textOnly = false) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        $isMessage = isset($event['data']['message']);
        $hasText = isset($event['data']['message']['text']);
        if (!$isMessage || !$hasText) return false;

        return !$this->textOnly || !isset($event['data']['message']['entities']);
    }
}