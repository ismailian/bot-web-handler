<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class InlineQuery implements IEvent
{

    /**
     * default constructor
     *
     * @param bool $allowEmpty capture empty query
     */
    public function __construct(public bool $allowEmpty = false) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        if (isset($event['data']['inline_query']))
            return $this->allowEmpty || !empty($event['data']['inline_query']['query']);

        return false;
    }
}