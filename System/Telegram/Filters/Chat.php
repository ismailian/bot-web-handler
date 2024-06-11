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
class Chat implements IEvent
{

    const string PRIVATE = 'private';
    const string CHANNEL = 'channel';
    const string GROUP = 'group';
    const string SUPERGROUP = 'supergroup';

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