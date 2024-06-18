<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Types;

use TeleBot\System\Telegram\Enums\InlineChatType;

class IncomingInlineQuery
{

    /** @var string $id inline query id */
    public string $id;

    /** @var User $from inline query sender */
    public User $from;

    /** @var InlineChatType $chatType */
    public InlineChatType $chatType;

    /** @var string $query inline query content */
    public string $query;

    /** @var string $offset inline query offset */
    public string $offset;

    /** @var bool $isEmpty is query empty? */
    public bool $isEmpty;

    /**
     * default constructor
     *
     * @param array $inlineQuery
     */
    public function __construct(protected readonly array $inlineQuery)
    {
        $this->id = $this->inlineQuery['id'];
        $this->isEmpty = empty($this->query);
        $this->query = $this->inlineQuery['query'];
        $this->offset = $this->inlineQuery['offset'];
        $this->from = new User($this->inlineQuery['from']);
        $this->chatType = match ($this->inlineQuery['chat_type']) {
            'sender' => InlineChatType::SENDER,
            'private' => InlineChatType::PRIVATE,
            'channel' => InlineChatType::CHANNEL,
            'group' => InlineChatType::GROUP,
            'supergroup' => InlineChatType::SUPERGROUP,
        };
    }

}