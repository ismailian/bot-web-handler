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
use TeleBot\System\Session\Session;

#[Attribute(Attribute::TARGET_METHOD)]
class Awaits implements IEvent
{

    /**
     * default constructor
     *
     * @param string $key
     * @param string $value
     */
    public function __construct(public string $key, public string $value) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        return Session::get($this->key) == $this->value;
    }
}