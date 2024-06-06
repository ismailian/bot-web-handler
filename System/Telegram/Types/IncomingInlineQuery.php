<?php

namespace TeleBot\System\Telegram\Types;

use TeleBot\System\Telegram\Enums\InlineChatType;

class IncomingInlineQuery
{

    /** @var string $id inline query id */
    public string $id;

    /** @var From $from inline query sender */
    public From $from;

    /** @var InlineChatType|string $chatType */
    public InlineChatType|string $chatType;

    /** @var string $query inline query content */
    public string $query;

    /** @var string $offset inline query offset */
    public string $offset;

    /** @var bool $isEmpty is query empty */
    public bool $isEmpty;

    /**
     * default constructor
     *
     * @param array $inlineQuery
     */
    public function __construct(protected array $inlineQuery)
    {
        $this->id = $this->inlineQuery['id'];
        $this->isEmpty = empty($this->query);
        $this->query = $this->inlineQuery['query'];
        $this->offset = $this->inlineQuery['offset'];
        $this->from = new From($this->inlineQuery['from']);
        $this->chatType = match ($this->inlineQuery['chat_type']) {
            'sender' => InlineChatType::SENDER,
            'private' => InlineChatType::PRIVATE,
            'channel' => InlineChatType::CHANNEL,
            'group' => InlineChatType::GROUP,
            'supergroup' => InlineChatType::SUPERGROUP,
        };
    }

}