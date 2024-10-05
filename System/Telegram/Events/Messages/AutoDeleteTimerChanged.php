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
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Traits\Messageable;
use TeleBot\System\Telegram\Types\MessageAutoDeleteTimerChanged;

#[Attribute(Attribute::TARGET_METHOD)]
class AutoDeleteTimerChanged implements IEvent
{

    use Messageable;

    /**
     * @inheritDoc
     */
    public function apply(array $event): MessageAutoDeleteTimerChanged|bool
    {
        if (!$this->isMessage(array_keys($event))) return false;

        $key = $this->first(array_keys($event));
        if (!array_key_exists(
            'message_auto_delete_timer_changed', $event[$key])
        ) return false;

        return new MessageAutoDeleteTimerChanged(
            $event[$key]['message_auto_delete_timer_changed']
        );
    }
}