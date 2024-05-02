<?php

namespace TeleBot\System\Types;

class CallbackQuery
{

    /** @var string $id callback id */
    public string $id;

    /** @var string $messageId source message id */
    public string $messageId;

    /** @var From $from sender */
    public From $from;

    /** @var mixed query data */
    public mixed $data;

    /**
     * default constructor
     *
     * @param array $callback
     */
    public function __construct(protected array $callback)
    {
        $this->id = $this->callback['id'];
        $this->messageId = $this->callback['inline_message_id'];
        $this->from = new From($this->callback['from']);
        $this->data = $this->callback['data'];

        if (($json = json_decode($this->data, true))) {
            $this->data = $json;
        }
    }

}