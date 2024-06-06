<?php

namespace TeleBot\System\Telegram\Filters;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Only implements IEvent
{

    /**
     * default constructor
     *
     * @param string|null $userId
     * @param array|null $userIds
     */
    public function __construct(
        public ?string $userId = null,
        public ?array $userIds = [],
    ) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        unset($event['update_id']);
        $keys = array_keys($event);
        $userId = $event[$keys[0]]['from']['id'];

        if (($this->userId && $this->userId != $userId)
            || ($this->userIds && !in_array($userId, $this->userIds))
        ) return false;
        return true;
    }
}