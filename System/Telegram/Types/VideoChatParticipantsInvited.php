<?php

namespace TeleBot\System\Telegram\Types;

class VideoChatParticipantsInvited
{

    /** @var User[] $users New members that were invited to the video chat */
    public array $users;

    /**
     * default constructor
     *
     * @param array $videoChatParticipantsInvited
     */
    public function __construct(protected readonly array $videoChatParticipantsInvited)
    {
        $this->users = array_map(
            fn($user) => new User($user),
            $this->videoChatParticipantsInvited['users']
        );
    }

}