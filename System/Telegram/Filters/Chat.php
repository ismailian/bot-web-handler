<?php

namespace TeleBot\System\Telegram\Filters;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Chat implements IEvent
{

    const PRIVATE = 'private';
    const CHANNEL = 'channel';
    const GROUP = 'group';
    const SUPERGROUP = 'supergroup';

    /**
     * default constructor
     *
     * @param string $chatType
     */
    public function __construct(public string $chatType = Chat::PRIVATE) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        unset($event['update_id']);
        $keys = array_keys($event);
        $chatType = $event[$keys[0]]['chat']['type'];

        return $chatType === $this->chatType;
    }
}