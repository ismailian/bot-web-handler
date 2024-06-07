<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Interfaces\IValidator;
use TeleBot\System\Telegram\Types\IncomingVideoNote;

#[Attribute(Attribute::TARGET_METHOD)]
class VideoNote implements IEvent
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
    public function apply(array $event): IncomingVideoNote|bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        if (!array_key_exists('video_note', $event[$key])) return false;

        if ($this->Validator && !$this->Validator->isValid($event[$key]['video_note'])) return false;
        return new IncomingVideoNote($event[$key]['video_note']);
    }
}