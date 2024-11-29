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

class User
{

    /** @var string $id user id */
    public string $id;

    /** @var string $firstName user's first name */
    public string $firstName;

    /** @var string|null $lastName users' last name */
    public ?string $lastName = null;

    /** @var string|null $username user's username */
    public ?string $username = null;

    /** @var string|null $languageCode user's language code */
    public ?string $languageCode = null;

    /** @var bool $isBot is user a bot */
    public bool $isBot;

    /** @var bool $isPremium is user premium */
    public bool $isPremium;

    /** @var bool $canJoinGroups can bot join groups */
    public bool $canJoinGroups;

    /** @var bool $canConnectToBusiness can bot connect to a business */
    public bool $canConnectToBusiness;

    /** @var bool $supportsInlineQueries bot supports inline queries */
    public bool $supportsInlineQueries;

    /** @var bool $addedToAttachmentMenu is added to the attachment menu */
    public bool $addedToAttachmentMenu;

    /** @var bool $canReadAllGroupMessages can read all group messages */
    public bool $canReadAllGroupMessages;

    /**
     * default constructor
     *
     * @param array $user
     */
    public function __construct(protected readonly array $user)
    {
        $this->id = $this->user['id'];
        $this->firstName = $this->user['first_name'];
        $this->lastName = $this->user['last_name'] ?? null;
        $this->username = $this->user['username'] ?? null;
        $this->languageCode = $this->user['language_code'] ?? null;

        $this->isBot = $this->user['is_bot'];
        $this->isPremium = $this->user['is_premium'] ?? false;
        $this->canJoinGroups = $this->user['can_join_groups'] ?? false;
        $this->canConnectToBusiness = $this->user['can_connect_to_business'] ?? false;
        $this->supportsInlineQueries = $this->user['supports_inline_queries'] ?? false;
        $this->addedToAttachmentMenu = $this->user['added_to_attachment_menu'] ?? false;
        $this->canReadAllGroupMessages = $this->user['can_read_all_group_messages'] ?? false;
    }

    /**
     * get array representation of this object
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->user;
    }

}