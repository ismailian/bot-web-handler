<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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