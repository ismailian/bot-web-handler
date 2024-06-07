<?php

namespace TeleBot\System\Telegram\Types;

class IncomingChosenInlineQuery
{

    /** @var string $resultId */
    public string $resultId;

    /** @var From $from user who chose query */
    public From $from;

    /** @var IncomingLocation|null $location */
    public ?IncomingLocation $location = null;

    /** @var string|null $inlineMessageId inline message id */
    public ?string $inlineMessageId = null;

    /** @var string $query */
    public string $query;

    /**
     * default constructor
     *
     * @param array $incomingChosenInlineQuery
     */
    public function __construct(protected array $incomingChosenInlineQuery)
    {
        $this->from = new From($this->incomingChosenInlineQuery['from']);
        $this->resultId = $this->incomingChosenInlineQuery['result_id'];
        $this->query = $this->incomingChosenInlineQuery['query'];

        if (array_key_exists('inline_message_id', $this->incomingChosenInlineQuery['inline_message_id'])) {
            $this->inlineMessageId = $this->incomingChosenInlineQuery['inline_message_id'];
        }

        if (array_key_exists('location', $this->incomingChosenInlineQuery)) {
            $this->location = new IncomingLocation($this->incomingChosenInlineQuery['location']);
        }
    }

}