<?php

namespace TeleBot\System\Telegram\Types;

class IncomingContact
{

    /** @var string $phoneNumber contact's phone number */
    public string $phoneNumber;

    /** @var string $firstName contact's first name */
    public string $firstName;

    /** @var string|null $lastName contact's last name */
    public ?string $lastName = null;

    /** @var string|null $userId contact's user id */
    public ?string $userId = null;

    /** @var string|null $vcard additional contact data */
    public ?string $vcard = null;

    /**
     * default constructor
     *
     * @param array $incomingContact
     */
    public function __construct(protected array $incomingContact)
    {
        $this->phoneNumber = $this->incomingContact['phone_number'];
        $this->firstName = $this->incomingContact['first_name'];

        if (array_key_exists('last_name', $this->incomingContact)) {
            $this->lastName = $this->incomingContact['last_name'];
        }

        if (array_key_exists('user_id', $this->incomingContact)) {
            $this->userId = $this->incomingContact['user_id'];
        }

        if (array_key_exists('vcard', $this->incomingContact)) {
            $this->vcard = $this->incomingContact['vcard'];
        }
    }

}