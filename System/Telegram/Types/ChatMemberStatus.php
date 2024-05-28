<?php

namespace TeleBot\System\Telegram\Types;

use DateTime;
use TeleBot\System\Telegram\Enums\MemberStatus;

class ChatMemberStatus
{

    /** @var MemberStatus|string $status current chat member status */
    public MemberStatus|string $status;

    /** @var DateTime|null $until in current state until */
    public ?DateTime $until = null;

    /** @var bool $canBeEdited */
    public bool $canBeEdited = false;

    /** @var bool $canManageChat */
    public bool $canManageChat = false;

    /** @var bool $canChangeInfo */
    public bool $canChangeInfo = false;

    /** @var bool $canDeleteMessages */
    public bool $canDeleteMessages = false;

    /** @var bool $canInviteUsers */
    public bool $canInviteUsers = false;

    /** @var bool $canRestrictMembers */
    public bool $canRestrictMembers;

    /** @var bool $canPinMessages */
    public bool $canPinMessages = false;

    /** @var bool $canPromoteMembers */
    public bool $canPromoteMembers = false;

    /** @var bool $canManageVideoChats */
    public bool $canManageVideoChats = false;

    /** @var bool $canPostStories */
    public bool $canPostStories = false;

    /** @var bool $canEditStories */
    public bool $canEditStories = false;

    /** @var bool $canDeleteStories */
    public bool $canDeleteStories = false;

    /** @var bool $canManageVoiceChats */
    public bool $canManageVoiceChats;

    /** @var bool $isAnonymous */
    public bool $isAnonymous = false;

    /**
     * default constructor
     *
     * @param array $oldChatMember
     * @param array $newChatMember
     */
    public function __construct(protected array $oldChatMember, protected array $newChatMember)
    {
        $this->until = $this->newChatMember['until'] ?? null;
        $this->status = match ($this->newChatMember['status']) {
            'member' => MemberStatus::MEMBER,
            'kicked' => MemberStatus::KICKED,
            'left' => MemberStatus::LEFT,
            'administrator' => MemberStatus::ADMIN,
        };
    }

}

