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

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingChosenInlineResult;

#[Attribute(Attribute::TARGET_METHOD)]
class ChosenInlineResult implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingChosenInlineResult|bool
    {
        if (!array_key_exists('chosen_inline_result', $event)) return false;
        return new IncomingChosenInlineResult($event['chosen_inline_result']);
    }
}