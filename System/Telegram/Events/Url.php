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
        $key = isset($event['data']['edited_message']) ? 'edited_message' : 'message';
        $isMessage = isset($event['data'][$key]);
        $hasText = isset($event['data'][$key]['text']);
        $hasEntities = !empty($event['data'][$key]['entities']);

        if (!$isMessage || !$hasText || !$hasEntities) return false;
        foreach ($event['data'][$key]['entities'] as $entity) {
            if ($entity['type'] == 'url') {
                $url = new IncomingUrl($event['data'][$key]['text'], $entity);
                if (!$this->Validator || $this->Validator->isValid($url)) {
                    return $url;
                }
            }
        }

        return false;
    }
}