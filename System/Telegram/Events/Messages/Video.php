<?php

namespace TeleBot\System\Telegram\Events\Messages;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Interfaces\IValidator;
use TeleBot\System\Telegram\Traits\Messageable;
use TeleBot\System\Telegram\Types\IncomingVideo;

#[Attribute(Attribute::TARGET_METHOD)]
class Video implements IEvent
{

    use Messageable;

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
        if (!$this->isMessage(array_keys($event))) return false;

        $key = $this->first(array_keys($event));
        if (!array_key_exists('video', $event[$key])) return false;

        if ($this->Validator && !$this->Validator->isValid($event[$key]['video'])) return false;
        return new IncomingVideo($event[$key]['video']);
    }
}