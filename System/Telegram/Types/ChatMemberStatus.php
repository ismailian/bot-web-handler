<?php

namespace TeleBot\System\Telegram\Types;

use DateTime;
use DateTimeZone;
use Exception;
use TeleBot\System\Telegram\Enums\MemberStatus;

class ChatMemberStatus
{

    /** @var MemberStatus|string $status current chat member status */
    public MemberStatus|string $status;

    /** @var DateTime|null $until in current state until */
    public ?DateTime $until = null;

    /** @var string|null $customTitle custom title */
    public ?string $customTitle = null;

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
     * @throws Exception
     */
    public function __construct(protected array $oldChatMember, protected array $newChatMember)
    {
        if (array_key_exists('until_date', $this->newChatMember)) {
            if ($this->newChatMember['until_date'] > 0) {
                $this->until = new DateTime(
                    date('Y-m-d H:i:s', strtotime($this->newChatMember['until_date']))
                );
            }
        }

        $this->status = match ($this->newChatMember['status']) {
            'creator' => MemberStatus::OWNER,
            'administrator' => MemberStatus::ADMIN,
            'member' => MemberStatus::MEMBER,
            'restricted' => MemberStatus::RESTRICTED,
            'kicked' => MemberStatus::BANNED,
            'left' => MemberStatus::LEFT,
        };
    }

}