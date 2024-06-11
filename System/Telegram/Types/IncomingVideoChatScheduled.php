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

use DateTime;
use Exception;

class IncomingVideoChatScheduled
{

    /** @var DateTime $startDate Point in time (Unix timestamp) when the video chat is supposed to be started by a chat administrator */
    public DateTime $startDate;

    /**
     * default constructor
     *
     * @param array $incomingVideoChatScheduled
     * @throws Exception
     */
    public function __construct(protected readonly array $incomingVideoChatScheduled)
    {
        $this->startDate = new DateTime(
            date('Y-m-d H:i:s', $this->incomingVideoChatScheduled['start_date'])
        );
    }

}