<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Traits;

use GuzzleHttp\Exception\GuzzleException;
use TeleBot\System\Telegram\BotApi;

trait Extensions
{

    /** @var string $chatId */
    protected string $chatId = '';

    /** @var string $token */
    protected string $token = '';

    /** @var string $mode */
    protected string $mode = 'html';

    /** @var array $options options to send with the message */
    protected array $options = [];

    /** @var string|null $lastMessageId */
    protected ?string $lastMessageId = null;

    /** @var ?string $lastUploadId */
    protected ?string $lastUploadId = null;

    /**
     * set bot token
     *
     * @param string $token bot token to be used in requests
     * @return BotApi|Extensions
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * set message parse mode
     *
     * @param string $mode mode to use with message
     * @return BotApi|Extensions
     */
    public function setParseMode(string $mode = 'text'): self
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * set recipient chat id
     *
     * @param string $chatId recipient chat id
     * @return BotApi|Extensions
     */
    public function setChatId(string $chatId): self
    {
        $this->chatId = $chatId;
        return $this;
    }

    /**
     * extra options to send with the message
     *
     * @param array $options
     * @return BotApi|Extensions
     */
    public function withOptions(array $options): self
    {
        $this->options = [...$this->options, ...$options];
        return $this;
    }

    /**
     * send an action
     *
     * @param string $action action to send
     * @return BotApi|Extensions
     * @throws GuzzleException
     */
    public function withAction(string $action): self
    {
        $this->post('action', [
            'chat_id' => $this->chatId,
            'action' => $action
        ]);

        return $this;
    }

    /**
     * set message to reply to
     *
     * @param int $messageId
     * @param string|null $chatId
     * @return BotApi|Extensions
     */
    public function replyTo(int $messageId, string $chatId = null): self
    {
        $this->options['reply_parameters']['message_id'] = $messageId;
        if ($chatId) {
            $this->options['reply_parameters']['chat_id'] = $chatId;
        }

        return $this;
    }

    /**
     * delete last message sent by bot
     *
     * @return BotApi|Extensions
     * @throws GuzzleException
     */
    public function deleteLastMessage(): self
    {
        if (!empty($this->lastMessageId)) {
            $this->deleteMessage($this->lastMessageId);
            $this->lastMessageId = null;
        }

        return $this;
    }

    /**
     * get last sent message id
     *
     * @return int|null
     */
    public function getLastMessageId(): ?int
    {
        return $this->lastMessageId ?? null;
    }

    /**
     * get last sent video id
     *
     * @return string|null
     */
    public function getLastUploadId(): ?string
    {
        return $this->lastUploadId;
    }

}