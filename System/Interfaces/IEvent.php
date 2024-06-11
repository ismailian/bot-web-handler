<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Interfaces;

interface IEvent
{

    /**
     * verify event type
     *
     * @param array $event
     * @return mixed
     */
    public function apply(array $event): mixed;

}