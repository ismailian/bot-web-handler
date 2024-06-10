<?php

namespace TeleBot\System\Telegram\Types;

use DateTime;
use Exception;

class IncomingChatJoinRequest
{

    /** @var Chat $chat chat to which request was sent */
    public Chat $chat;

    /** @var User $from user who sent the join request */
    public User $from;

    /** @var string $userChatId user chat id */
    public string $userChatId;

    /** @var DateTime $date join request date */
    public DateTime $date;

    /** @var string|null $bio user bio */
    public ?string $bio = null;

    /** @var ChatInviteLink|null $inviteLink invite link */
    public ?ChatInviteLink $inviteLink = null;

    /**
     * default constructor
     *
     * @param array $incomingChatJoinRequest
     * @throws Exception
     */
    public function __construct(protected readonly array $incomingChatJoinRequest)
    {
        $this->chat = new Chat($this->incomingChatJoinRequest['chat']);
        $this->from = new User($this->incomingChatJoinRequest['from']);
        $this->userChatId = $this->incomingChatJoinRequest['user_chat_id'];
        $this->bio = $this->incomingChatJoinRequest['bio'] ?? null;
        $this->date = new DateTime(date('Y-m-d H:i:s', strtotime($this->incomingChatJoinRequest['date'])));

        if (array_key_exists('invite_line', $this->incomingChatJoinRequest)) {
            $this->inviteLink = new ChatInviteLink($this->incomingChatJoinRequest['invite_link']);
        }
    }

}