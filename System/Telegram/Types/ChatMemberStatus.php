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
        };
    }

}

