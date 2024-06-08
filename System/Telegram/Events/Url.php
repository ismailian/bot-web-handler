<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Interfaces\IValidator;
use TeleBot\System\Telegram\Types\IncomingUrl;

#[Attribute(Attribute::TARGET_METHOD)]
class Url implements IEvent
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
    public function apply(array $event): bool|IncomingUrl
    {
        $updates = ['message', 'edited_message'];
        if (empty($result = array_intersect($updates, array_keys($event)))) return false;
        
        $key = array_values($result)[0];
        if (!array_key_exists('text', $event[$key])) return false;
        if (!array_key_exists('entities', $event[$key])) return false;
        if (empty($event[$key]['entities'])) return false;

        foreach ($event[$key]['entities'] as $entity) {
            if ($entity['type'] == 'url') {
                $url = new IncomingUrl($event[$key]['text'], $entity);
                if (!$this->Validator || $this->Validator->isValid($url)) {
                    return $url;
                }
            }
        }

        return false;
    }
}