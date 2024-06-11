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
use TeleBot\System\Telegram\Types\IncomingMessage;

class BusinessMessage implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingMessage|bool
    {
        if (!array_key_exists('business_message', $event)) return false;
        return new IncomingMessage($event['business_message']);
    }
}