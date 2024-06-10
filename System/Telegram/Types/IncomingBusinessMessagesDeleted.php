<?php

namespace TeleBot\System\Telegram\Types;

class IncomingBusinessMessagesDeleted
{

    /** @var string $businessConnectionId business connection id */
    public string $businessConnectionId;

    /** @var Chat $chat chat in the business account */
    public Chat $chat;

    /** @var array $messageIds list of deleted messages ids */
    public array $messageIds;

    /**
     * default constructor
     *
     * @param array $incomingBusinessMessageDeleted
     */
    public function __construct(protected readonly array $incomingBusinessMessageDeleted)
    {
        $this->chat = new Chat($incomingBusinessMessageDeleted['chat']);
        $this->messageIds = $incomingBusinessMessageDeleted['message_ids'];
        $this->businessConnectionId = $incomingBusinessMessageDeleted['business_connection_id'];
    }

}