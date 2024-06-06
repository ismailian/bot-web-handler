<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingCommand;

#[Attribute(Attribute::TARGET_METHOD)]
class Command implements IEvent
{

    /**
     * default constructor
     *
     * @param ?string $command
     */
    public function __construct(public ?string $command = null) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingCommand|bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        if (!empty($this->command))
            $this->command = str_starts_with($this->command, '/') ? '' : "/{$this->command}";

        foreach ($event[$key]['entities'] ?? [] as $entity) {
            if ($entity['type'] == 'bot_command') {
                if (!$this->command || substr($event[$key]['text'], $entity['offset'], $entity['length']) == $this->command) {
                    return new IncomingCommand($event[$key]['text'], $entity);
                }
            }
        }

        return false;
    }
}