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

class IncomingChatBoostRemoved
{

    /** @var string $boostId boost id */
    public string $boostId;

    /** @var Chat $chat chat which was boosted */
    public Chat $chat;

    /** @var DateTime $removeDate removal data */
    public DateTime $removeDate;

    /** @var ChatBoostSource $source boost source */
    public ChatBoostSource $source;

    /**
     * default constructor
     *
     * @param array $incomingChatBoostRemoved
     * @throws Exception
     */
    public function __construct(protected readonly array $incomingChatBoostRemoved)
    {
        $this->boostId = $this->incomingChatBoostRemoved['boost_id'];
        $this->chat = new Chat($this->incomingChatBoostRemoved['chat']);
        $this->removeDate = new DateTime(date('Y-m-d H:i:s', $this->incomingChatBoostRemoved['remove_date']));
        $this->source = new ChatBoostSource($this->incomingChatBoostRemoved['source']);
    }
}