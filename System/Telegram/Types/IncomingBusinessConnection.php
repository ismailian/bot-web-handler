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

class IncomingBusinessConnection
{

    /** @var string $id business connection id */
    public string $id;

    /** @var User $user */
    public User $user;

    /** @var string $userChatId user chat id */
    public string $userChatId;

    /** @var DateTime $date business connection date */
    public DateTime $date;

    /** @var bool $canReply can act on behalf of business */
    public bool $canReply;

    /** @var bool $isEnabled is connection active? */
    public bool $isEnabled;

    /**
     * default constructor
     *
     * @param array $incomingBusinessConnection
     * @throws Exception
     */
    public function __construct(protected readonly array $incomingBusinessConnection)
    {
        $this->id = $incomingBusinessConnection['id'];
        $this->user = new User($incomingBusinessConnection['user']);
        $this->userChatId = $incomingBusinessConnection['userChatId'];
        $this->canReply = $incomingBusinessConnection['canReply'] ?? false;
        $this->isEnabled = $incomingBusinessConnection['isEnabled'] ?? false;
        $this->date = new DateTime(date('Y-m-d H:i:s', $incomingBusinessConnection['date']));
    }

}