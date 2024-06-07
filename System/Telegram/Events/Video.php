<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Interfaces\IValidator;
use TeleBot\System\Telegram\Types\IncomingVideo;

#[Attribute(Attribute::TARGET_METHOD)]
class Video implements IEvent
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
    public function apply(array $event): IncomingVideo|bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        if (!array_key_exists('video', $event[$key])) return false;

        if ($this->Validator && !$this->Validator->isValid($event[$key]['video'])) return false;
        return new IncomingVideo($event[$key]['video']);
    }
}