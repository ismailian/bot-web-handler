<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Events\Messages;

use Attribute;
use Exception;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Traits\Messageable;
use TeleBot\System\Telegram\Types\IncomingVideoChatScheduled;

#[Attribute(Attribute::TARGET_METHOD)]
class VideoChatScheduled implements IEvent
{

    use Messageable;

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(array $event): IncomingVideoChatScheduled|bool
    {
        if (!$this->isMessage(array_keys($event))) return false;
        if (array_key_exists('video_chat_scheduled', $event[$this->first(array_keys($event))])) {
            return new IncomingVideoChatScheduled($event[$this->first(array_keys($event))]);
        }

        return false;
    }
}