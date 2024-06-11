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

class IncomingChatMember
{

    /** @var DateTime $date event date */
    public DateTime $date;

    /** @var Chat $chat Chat object */
    public Chat $chat;

    /** @var User $from From object */
    public User $from;

    /** @var ChatMemberStatus $memberStatus chat member status object */
    public ChatMemberStatus $memberStatus;

    /**
     * default constructor
     *
     * @param array $myChatMember
     * @throws Exception
     */
    public function __construct(protected readonly array $myChatMember)
    {
        $this->date = new DateTime(date('Y-m-d H:i:s T', $this->myChatMember['date']));
        $this->chat = new Chat($this->myChatMember['chat']);
        $this->from = new User($this->myChatMember['from']);

        $this->memberStatus = new ChatMemberStatus(
            $this->myChatMember['old_chat_member'],
            $this->myChatMember['new_chat_member']
        );
    }

}