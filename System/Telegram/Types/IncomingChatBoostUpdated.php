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

use Exception;

class IncomingChatBoostUpdated
{

    /** @var Chat $chat chat which was boosted */
    public Chat $chat;

    /** @var ChatBoost $boost information about the chat boost */
    public ChatBoost $boost;

    /**
     * default constructor
     *
     * @param array $incomingChatBoostUpdated
     * @throws Exception
     */
    public function __construct(protected readonly array $incomingChatBoostUpdated)
    {
        $this->chat = new Chat($this->incomingChatBoostUpdated['chat']);
        $this->boost = new ChatBoost($this->incomingChatBoostUpdated['boost']);
    }

}