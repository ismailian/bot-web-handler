<?php

namespace TeleBot\System\Telegram\Types;

use DateTime;
use Exception;

class ChatInviteLink
{

    /** @var string $inviteLink invite link */
    public string $inviteLink;

    /** @var User $creator creator of the link */
    public User $creator;

    /** @var bool $createsJoinRequest true if joining using this link create a join requests */
    public bool $createsJoinRequest;

    /** @var bool $isPrimary is primary link */
    public bool $isPrimary;

    /** @var bool $isRevoked is revoked link */
    public bool $isRevoked;

    /** @var string|null $name invite link name */
    public ?string $name = null;

    /** @var DateTime|null $expireDate expire date */
    public ?DateTime $expireDate = null;

    /** @var int|null $memberLimit maximum number of users to join via this link */
    public ?int $memberLimit = null;

    /** @var int $pendingJoinRequestCount pending join requests */
    public int $pendingJoinRequestCount = 0;

    /**
     * default constructor
     *
     * @param array $chatInviteLink
     * @throws Exception
     */
    public function __construct(protected array $chatInviteLink)
    {
        $this->inviteLink = $this->chatInviteLink['invite_link'];
        $this->creator = new User($this->chatInviteLink['creator']);
        $this->createsJoinRequest = $this->chatInviteLink['creates_join_request'];
        $this->isPrimary = $this->chatInviteLink['is_primary'];
        $this->isRevoked = $this->chatInviteLink['is_revoked'];
        $this->name = $this->chatInviteLink['name'] ?? null;
        $this->memberLimit = $this->chatInviteLink['member_limit'] ?? null;
        $this->pendingJoinRequestCount = $this->chatInviteLink['pending_join_request_count'] ?? null;

        if (array_key_exists('expire_date', $this->chatInviteLink)) {
            $this->expireDate = new DateTime(
                date('Y-m-d H:i:s', $this->chatInviteLink['expire_date'])
            );
        }
    }
}