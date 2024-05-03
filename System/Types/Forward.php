<?php

namespace TeleBot\System\Types;

use DateTime;
use Exception;

class Forward
{

    /** @var DateTime $date forward date */
    public DateTime $date;

    /** @var string $type source chat type */
    public string $type = 'user';

    /** @var int|null $forwardFromMessageId forwarded message id */
    public ?int $forwardFromMessageId = null;

    /** @var string|null $forwardSignature forward signature */
    public ?string $forwardSignature = null;

    /** @var Origin $origin message origin */
    public Origin $origin;

    /**
     * default constructor
     *
     * @param array $message
     * @throws Exception
     */
    public function __construct(protected array $message)
    {
        $this->date = new DateTime(date('Y-m-d H:i:s T', $this->message['forward_date']));
        $this->type = $this->message['forward_origin']['type'];

        $this->forwardFromMessageId = $this->message['forward_from_message_id'] ?? null;
        $this->forwardSignature = $this->message['forward_signature'] ?? null;

        $this->origin = new Origin($this->message['forward_origin']);
    }

}