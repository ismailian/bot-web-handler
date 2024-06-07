<?php

namespace TeleBot\System\Telegram\Types;

class IncomingChosenInlineResult
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
     * @param array $incomingChosenInlineResult
     */
    public function __construct(protected array $incomingChosenInlineResult)
    {
        $this->from = new From($this->incomingChosenInlineResult['from']);
        $this->resultId = $this->incomingChosenInlineResult['result_id'];
        $this->query = $this->incomingChosenInlineResult['query'];

        if (array_key_exists('inline_message_id', $this->incomingChosenInlineResult['inline_message_id'])) {
            $this->inlineMessageId = $this->incomingChosenInlineResult['inline_message_id'];
        }

        if (array_key_exists('location', $this->incomingChosenInlineResult)) {
            $this->location = new IncomingLocation($this->incomingChosenInlineResult['location']);
        }
    }

}