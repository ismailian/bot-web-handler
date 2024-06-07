<?php

namespace TeleBot\System\Telegram\Types;

class IncomingCallbackQuery
{

    /** @var string $id callback id */
    public string $id;

    /** @var string $chatInstance chat instance */
    public string $chatInstance;

    /** @var From $from sender */
    public From $from;

    /** @var IncomingMessage $message callback message */
    public IncomingMessage $message;

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
        $this->chatInstance = $this->callback['chat_instance'];

        $this->from = new From($this->callback['from']);
        $this->message = new IncomingMessage($this->callback['message']);
        $this->data = $this->callback['data'];

        if (($json = json_decode($this->data, true))) {
            $this->data = $json;
        }
    }

}