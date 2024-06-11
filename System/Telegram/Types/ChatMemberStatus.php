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
    public function __construct(protected readonly array $oldChatMember, protected array $newChatMember)
    {
        $this->customTitle = $this->newChatMember['custom_title'] ?? null;
        $this->canBeEdited = $this->newChatMember['can_be_edited'] ?? false;
        $this->canManageChat = $this->newChatMember['can_manage_chat'] ?? false;
        $this->canChangeInfo = $this->newChatMember['can_change_info'] ?? false;
        $this->canDeleteMessages = $this->newChatMember['can_delete_messages'] ?? false;
        $this->canInviteUsers = $this->newChatMember['can_invite_users'] ?? false;
        $this->canRestrictMembers = $this->newChatMember['can_restrict_members'] ?? false;
        $this->canPinMessages = $this->newChatMember['can_pin_messages'] ?? false;
        $this->canPromoteMembers = $this->newChatMember['can_promote_members'] ?? false;
        $this->canManageVideoChats = $this->newChatMember['can_manage_video_chats'] ?? false;
        $this->canPostStories = $this->newChatMember['can_post_stories'] ?? false;
        $this->canEditStories = $this->newChatMember['can_edit_stories'] ?? false;
        $this->canDeleteStories = $this->newChatMember['can_delete_stories'] ?? false;
        $this->canManageVoiceChats = $this->newChatMember['can_manage_voice_chats'] ?? false;
        $this->isAnonymous = $this->newChatMember['is_anonymous'] ?? false;

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