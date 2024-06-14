<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Types;

class IncomingChatBoostAdded
{

    /** @var int $boostCount Number of boosts added by the user */
    public int $boostCount;

    /**
     * default constructor
     *
     * @param array $incomingChatBoostAdded
     */
    public function __construct(protected readonly array $incomingChatBoostAdded)
    {
        $this->boostCount = count($this->incomingChatBoostAdded);
    }

}