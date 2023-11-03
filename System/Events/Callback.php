<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Callback implements IEvent
{

    /**
     * default constructor
     *
     * @param string $key
     */
    public function __construct(public string $key = '') {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        if (isset($event['data']['callback_query'])) {
            if (empty($this->key)) return true;

            $callbackData = $event['data']['callback_query']['callback_data'] ?? null;
            if (!$callbackData) return false;

            $callbackData = json_decode($callbackData, true);
            return (isset($callbackData['key']) && $callbackData['key'] == $this->key);
        }

        return false;
    }
}