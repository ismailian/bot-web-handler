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
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $isVideo = isset($event['data'][$key]['video_note']);
        if (!$isVideo) return false;

        if ($this->Validator && !$this->Validator->isValid($event['data'][$key]['video_note'])) return false;
        return new IncomingVideoNote($event['data'][$key]['video_note']);
    }
}