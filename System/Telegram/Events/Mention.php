<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Mention implements IEvent
{

    /**
     * default constructor
     *
     * @param string|null $username username to check for mentions
     */
    public function __construct(public ?string $username = null)
    {
        if ($this->username == 'me') {
            $this->username = getenv('TG_BOT_USERNAME', true);
        }
    }

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        if (!array_key_exists($key, $event)) return false;
        if (!array_key_exists('text', $event[$key])) return false;
        if (!array_key_exists('entities', $event[$key])) return false;
        if (empty($event[$key]['entities'])) return false;

        foreach ($event[$key]['entities'] as $entity) {
            if (isset($entity['type']) && $entity['type'] == 'mention') {
                return !$this->username || substr(
                    $event[$key]['text'], $entity['offset'], $entity['length']
                ) == $this->username;
            }
        }

        return false;
    }
}