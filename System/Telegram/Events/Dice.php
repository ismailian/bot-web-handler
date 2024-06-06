<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Interfaces\IValidator;
use TeleBot\System\Telegram\Types\IncomingDice;

#[Attribute(Attribute::TARGET_METHOD)]
class Dice implements IEvent
{

    /**
     * default constructor
     *
     * @param IValidator|null $Validator
     */
    public function __construct(public ?IValidator $Validator = null) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingDice|bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        $isDice = isset($event[$key]['dice']);
        if (!$isDice || ($this->Validator && !$this->Validator->isValid($event[$key]['dice'])))
            return false;

        return new IncomingDice($event[$key]['dice']);
    }
}