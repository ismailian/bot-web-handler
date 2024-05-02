<?php

namespace TeleBot\System\Types;

class Chat
{

    /** @var string $id chat id */
    public string $id;

    /** @var string $chat type */
    public string $type = 'type';

    /** @var string|null $username unique username */
    public ?string $username = null;

    /** @var string|null first name */
    public ?string $firstName = null;

    /** @var string|null $lastName last name */
    public ?string $lastName = null;

    /**
     * default constructor
     *
     * @param array $chat
     */
    public function __construct(protected array $chat)
    {
        $this->id = $this->chat['id'];
        $this->type = $this->chat['type'];
        $this->username = $this->chat['username'] ?? null;
        $this->firstName = $this->chat['first_name'] ?? null;
        $this->lastName = $this->chat['last_name'] ?? null;
    }

}