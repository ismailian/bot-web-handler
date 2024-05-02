<?php

namespace TeleBot\System\Types;

class Forward
{

    /** @var int $id source chat id */
    public int $id;

    /** @var string|null first name */
    public ?string $firstName = null;

    /** @var string|null last name */
    public ?string $lastName = null;

    /** @var string $type source chat type */
    public string $type = 'private';

    /** @var string|null source chat title */
    public ?string $title = null;

    /**
     * default constructor
     *
     * @param array $from
     * @param int $date
     */
    public function __construct(protected array $from, protected int $date)
    {
        $this->id = (int) $this->from['id'];
        $this->type = $this->from['type'] ?? 'private';
        $this->title = $this->from['title'] ?? null;
        $this->firstName = $this->from['first_name'] ?? null;
        $this->lastName = $this->from['last_name'] ?? null;
    }

}