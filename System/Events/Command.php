<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Command implements IEvent
{

    /**
     * default constructor
     *
     * @param string $command
     */
    public function __construct(public string $command) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        $this->command = trim($this->command, '/');
        if (str_contains($event['data']['message']['text'], "/{$this->command}")) {
            foreach ($event['data']['message']['entities'] as $entity) {
                if (isset($entity['bot_command']) && $entity['bot_command']) {
                    return true;
                }
            }
        }

        return false;
    }
}