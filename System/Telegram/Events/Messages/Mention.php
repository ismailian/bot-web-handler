<?php

namespace TeleBot\System\Telegram\Events\Messages;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Traits\Messageable;

#[Attribute(Attribute::TARGET_METHOD)]
class Mention implements IEvent
{

    use Messageable;

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
        if (!$this->isMessage(array_keys($event))) return false;
        $key = $this->first(array_keys($event));

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