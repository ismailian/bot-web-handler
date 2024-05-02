<?php

namespace TeleBot\System\Types;

class From
{

    /** @var string $id chat id */
    public string $id;

    /** @var string|null $username unique username */
    public ?string $username = null;

    /** @var string|null first name */
    public ?string $firstName = null;

    /** @var string|null $lastName last name */
    public ?string $lastName = null;

    /**
     * default constructor
     *
     * @param array $from
     */
    public function __construct(protected array $from)
    {
        $this->id = $this->from['id'];
        $this->username = $this->from['username'] ?? null;
        $this->firstName = $this->from['first_name'] ?? null;
        $this->lastName = $this->from['last_name'] ?? null;
    }

}