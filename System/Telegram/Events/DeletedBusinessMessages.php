<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Events;

use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingBusinessMessagesDeleted;

class DeletedBusinessMessages implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingBusinessMessagesDeleted|bool
    {
        if (!array_key_exists('deleted_business_messages', $event)) return false;
        return new IncomingBusinessMessagesDeleted($event['deleted_business_messages']);
    }
}