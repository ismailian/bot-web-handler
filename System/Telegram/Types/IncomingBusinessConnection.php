<?php

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