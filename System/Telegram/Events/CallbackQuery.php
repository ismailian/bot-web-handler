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
use TeleBot\System\Telegram\Types\IncomingCallbackQuery;

#[Attribute(Attribute::TARGET_METHOD)]
class CallbackQuery implements IEvent
{

    /**
     * default constructor
     *
     * @param string|null $key property name in the callback data
     * @param string|null $value property value in the callback data
     */
    public function __construct(public ?string $key = null, public ?string $value = null) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingCallbackQuery|bool
    {
        if (isset($event['callback_query'])) {
            if (!$this->key && !$this->value) return true;
            if (!empty(($callbackQuery = new IncomingCallbackQuery($event['callback_query'])))) {
                if (array_key_exists($this->key, $callbackQuery->data)) {
                    if ($callbackQuery->data[$this->key] === $this->value) {
                        return $callbackQuery;
                    }
                }
            }
        }

        return false;
    }
}