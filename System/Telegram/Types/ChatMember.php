<?php

namespace TeleBot\System\Telegram\Types;

use DateTime;
use Exception;

class ChatMember
{

    /** @var DateTime $date event date */
    public DateTime $date;

    /** @var Chat $chat Chat object */
    public Chat $chat;

    /** @var From $from From object */
    public From $from;

    /** @var ChatMemberStatus $memberStatus chat member status object */
    public ChatMemberStatus $memberStatus;

    /**
     * default constructor
     *
     * @param array $myChatMember
     * @throws Exception
     */
    public function __construct(protected array $myChatMember)
    {
        $this->date = new DateTime(date('Y-m-d H:i:s T', $this->myChatMember['date']));
        $this->chat = new Chat($this->myChatMember['chat']);
        $this->from = new From($this->myChatMember['from']);

        $this->memberStatus = new ChatMemberStatus(
            $this->myChatMember['old_chat_member'],
            $this->myChatMember['new_chat_member']
        );
    }

}