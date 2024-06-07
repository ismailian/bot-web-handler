<?php

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
    public function __construct(protected array $incomingChatBoostRemoved)
    {
        $this->boostId = $this->incomingChatBoostRemoved['boost_id'];
        $this->chat = new Chat($this->incomingChatBoostRemoved['chat']);
        $this->removeDate = new DateTime(date('Y-m-d H:i:s', $this->incomingChatBoostRemoved['remove_date']));
        $this->source = new ChatBoostSource($this->incomingChatBoostRemoved['source']);
    }
}