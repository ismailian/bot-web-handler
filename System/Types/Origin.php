<?php

namespace TeleBot\System\Types;

use DateTime;
use Exception;

class Origin
{

    /** @var ?int $id */
    public ?int $id = null;

    /** @var DateTime $date message date */
    public DateTime $date;

    /** @var string $type origin type */
    public string $type = 'user';

    /** @var string|null $signature author signature */
    public ?string $signature = null;

    /** @var Chat|null $chat chat */
    public ?Chat $chat = null;

    /** @var From|null $from message sender */
    public ?From $from = null;

    /**
     * default constructor
     *
     * @param array $origin
     * @throws Exception
     */
    public function __construct(protected array $origin)
    {
        $this->id = $this->origin['message_id'] ?? null;
        $this->date = new DateTime(date('Y-m-d H:i:s T', $this->origin['date']));

        $this->type = $this->origin['type'];
        $this->signature = $this->origin['author_signature'] ?? null;

        if ($this->type == 'user') {
            $this->from = new From($this->origin['sender_user']);
        } else {
            $this->chat = new Chat($this->origin['chat']);
        }
    }

}