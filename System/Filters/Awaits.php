<?php

namespace TeleBot\System\Filters;

use Attribute;
use TeleBot\System\Session;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Awaits implements IEvent
{

    /**
     * default constructor
     *
     * @param string $key
     * @param string $value
     */
    public function __construct(public string $key, public string $value) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        return Session::get($this->key) == $this->value;
    }
}