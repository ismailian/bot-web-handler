<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Types\IncomingCallbackQuery;

#[Attribute(Attribute::TARGET_METHOD)]
class CallbackQuery implements IEvent
{

    /**
     * default constructor
     *
     * @param string|null $key property name in the callback data
     * @param string|null $value property value in the callback data
     */
    public function __construct(public ?string $key = null, public ?string $value = null) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingCallbackQuery|bool
    {
        if (isset($event['data']['callback_query'])) {
            if (!$this->key && !$this->value) return true;
            if (!empty(($data = $event['data']['callback_query']['data']))) {
                if (($data = json_decode($data, true))) {
                    if (array_key_exists($this->key, $data))
                        if (!$this->value || $data[$this->key] == $this->value)
                            return new IncomingCallbackQuery($data);
                }
            }
        }

        return false;
    }
}