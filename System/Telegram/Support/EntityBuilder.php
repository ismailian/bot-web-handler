<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Support;

use TeleBot\System\Interfaces\IBuilder;
use TeleBot\System\Telegram\Types\User;

class EntityBuilder implements IBuilder
{

    /**
     * custom_emoji
     */

    /** @var array $entities list of entities */
    protected array $entities = [];

    /**
     * default constructor
     */
    public function __construct()
    {
        $this->entities = [];
    }

    /**
     * add entity of type MENTION
     *
     * @param int $offset
     * @param int $length
     * @return self
     */
    public function mention(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => __FUNCTION__,
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type HASHTAG
     *
     * @param int $offset
     * @param int $length
     * @return $this
     */
    public function hashtag(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => __FUNCTION__,
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type CODE
     *
     * @param int $offset
     * @param int $length
     * @return self
     */
    public function code(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => __FUNCTION__,
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type PRE
     *
     * @param int $offset
     * @param int $length
     * @param string|null $language
     * @return self
     */
    public function pre(int $offset, int $length, ?string $language = null): self
    {
        $this->entities[] = [
            'type' => __FUNCTION__,
            'offset' => $offset,
            'length' => $length,
            ...($language ? ['language' => $language] : [])
        ];

        return $this;
    }

    /**
     * add entity of type HASHTAG
     *
     * @param int $offset
     * @param int $length
     * @return $this
     */
    public function cashtag(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => __FUNCTION__,
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type BOLD
     *
     * @param int $offset
     * @param int $length
     * @return self
     */
    public function bold(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => __FUNCTION__,
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type TEXT_LINK
     *
     * @param int $offset
     * @param int $length
     * @param string|null $url
     * @return self
     */
    public function textLink(int $offset, int $length, ?string $url = null): self
    {
        $this->entities[] = [
            'type' => 'text_link',
            'offset' => $offset,
            'length' => $length,
            ...($url ? ['url' => $url] : [])
        ];

        return $this;
    }

    /**
     * add entity of type TEXT_MENTION
     *
     * @param int $offset
     * @param int $length
     * @param User|null $user
     * @return self
     */
    public function textMention(int $offset, int $length, ?User $user = null): self
    {
        $this->entities[] = [
            'type' => 'text_mention',
            'offset' => $offset,
            'length' => $length,
            ...($user ? ['user' => $user->toArray()] : [])
        ];

        return $this;
    }

    /**
     * get array of entities
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->entities;
    }

    /**
     * add entity of type ITALIC
     *
     * @param int $offset
     * @param int $length
     * @return $this
     */
    public function italic(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => __FUNCTION__,
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type STRIKETHROUGH
     *
     * @param int $offset
     * @param int $length
     * @return $this
     */
    public function strikethrough(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => __FUNCTION__,
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type UNDERLINE
     *
     * @param int $offset
     * @param int $length
     * @return self
     */
    public function underline(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => __FUNCTION__,
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type SPOILER
     *
     * @param int $offset
     * @param int $length
     * @return self
     */
    public function spoiler(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => __FUNCTION__,
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type BOT_COMMAND
     *
     * @param int $offset
     * @param int $length
     * @return self
     */
    public function command(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => 'bot_command',
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type URL
     *
     * @param int $offset
     * @param int $length
     * @return self
     */
    public function url(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => __FUNCTION__,
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type EMAIL
     *
     * @param int $offset
     * @param int $length
     * @return self
     */
    public function email(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => __FUNCTION__,
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type PHONE_NUMBER
     *
     * @param int $offset
     * @param int $length
     * @return self
     */
    public function phoneNumber(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => 'phone_number',
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type BLOCK_QUOTE
     *
     * @param int $offset
     * @param int $length
     * @return self
     */
    public function blockQuote(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => 'block_quote',
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * @param int $offset
     * @param int $length
     * @return self
     */
    public function expandableBlockQuote(int $offset, int $length): self
    {
        $this->entities[] = [
            'type' => 'expandable_blockquote',
            'offset' => $offset,
            'length' => $length
        ];

        return $this;
    }

    /**
     * add entity of type CUSTOM_EMOJI
     *
     * @param int $offset
     * @param int $length
     * @param string|null $customEmojiId
     * @return self
     */
    public function customEmoji(int $offset, int $length, ?string $customEmojiId = null): self
    {
        $this->entities[] = [
            'type' => 'custom_emoji',
            'offset' => $offset,
            'length' => $length,
            ...($customEmojiId ? ['custom_emoji_id' => $customEmojiId] : []),
        ];

        return $this;
    }

}