<?php

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
    public function __construct(protected array $incomingChatBoostUpdated)
    {
        $this->chat = new Chat($this->incomingChatBoostUpdated['chat']);
        $this->boost = new ChatBoost($this->incomingChatBoostUpdated['boost']);
    }

}