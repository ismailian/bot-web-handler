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
class Awaits implements IEvent
{

    /**
     * default constructor
     *
     * @param string $key session key
     * @param string $value session value
     * @param bool $reset if true, session key will be removed upon success
     */
    public function __construct(
        protected string $key,
        protected string $value,
        protected bool   $reset = false
    ) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        $result = session()->get($this->key) == $this->value;
        if ($result && $this->reset) {
            session()->unset($this->key);
        }

        return $result;
    }

}