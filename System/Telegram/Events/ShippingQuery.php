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
use TeleBot\System\Telegram\Types\IncomingShippingQuery;

#[Attribute(Attribute::TARGET_METHOD)]
class ShippingQuery implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingShippingQuery|bool
    {
        if (!array_key_exists('shipping_query', $event)) return false;
        return new IncomingShippingQuery($event['shipping_query']);
    }
}