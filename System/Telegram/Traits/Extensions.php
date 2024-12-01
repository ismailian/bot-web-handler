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

use TeleBot\System\Telegram\BotApi;
use TeleBot\System\Telegram\Types\InlineKeyboard;
use TeleBot\System\Telegram\Support\EntityBuilder;
use TeleBot\System\Telegram\Support\ReplyMarkupBuilder;
use TeleBot\System\Telegram\Support\ReplyParametersBuilder;

trait Extensions
{

    /** @var string $chatId */
    protected string $chatId = '';

    /** @var string $token */
    protected string $token = '';

    /** @var string|null $mode */
    protected ?string $mode = null;

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
     * add reply markup
     *
     * @param callable $markupBuilder
     * @return Extensions|BotApi
     */
    public function withReplyMarkup(callable $markupBuilder): self
    {
        $replyMarkup = $markupBuilder(new ReplyMarkupBuilder());
        if (!($replyMarkup instanceof ReplyMarkupBuilder) && !is_array($replyMarkup)) {
            return $this;
        }

        if ($replyMarkup instanceof ReplyMarkupBuilder) {
            $replyMarkup = $replyMarkup->toArray();
        }

        $this->options['reply_markup'] = [
            ...($this->options['reply_markup'] ?? []),
            ...$replyMarkup
        ];

        return $this;
    }

    /**
     * add reply parameters
     *
     * @param callable $parametersBuilder
     * @return Extensions|BotApi
     */
    public function withReplyParameters(callable $parametersBuilder): self
    {
        $replyParameters = $parametersBuilder(new ReplyParametersBuilder());
        if (!($replyParameters instanceof ReplyParametersBuilder) && !is_array($replyParameters)) {
            return $this;
        }

        if ($replyParameters instanceof ReplyParametersBuilder) {
            $replyParameters = $replyParameters->toArray();
        }

        $this->options['reply_parameters'] = [
            ...($this->options['reply_parameters'] ?? []),
            ...$replyParameters
        ];

        return $this;
    }

    /**
     * add an inline keyboard
     *
     * @param callable $keyboardBuilder
     * @return BotApi|Extensions
     */
    public function withInlineKeyboard(callable $keyboardBuilder): self
    {
        $inlineKeyboard = $keyboardBuilder(new InlineKeyboard());
        if (!($inlineKeyboard instanceof InlineKeyboard) && !is_array($inlineKeyboard)) {
            return $this;
        }

        if ($inlineKeyboard instanceof InlineKeyboard) {
            $inlineKeyboard = $inlineKeyboard->toArray();
        }

        $this->options['reply_markup'] = [
            ...($this->options['reply_markup'] ?? []),
            ...$inlineKeyboard
        ];

        return $this;
    }

    /**
     * add message entities
     *
     * @param callable $entityBuilder
     * @return Extensions|BotApi
     */
    public function withEntities(callable $entityBuilder): self
    {
        $entities = $entityBuilder(new EntityBuilder());
        if (!($entities instanceof EntityBuilder) && !is_array($entities)) {
            return $this;
        }

        if ($entities instanceof EntityBuilder) {
            $entities = $entities->toArray();
        }

        $this->options['entities'] = $entities;
        return $this;
    }

    /**
     * extra options to send with the message
     *
     * @param array $options
     * @param bool $reset
     * @return BotApi|Extensions
     */
    public function withOptions(array $options, bool $reset = false): self
    {
        $this->options = $reset ? $options : [...$this->options, ...$options];
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

}