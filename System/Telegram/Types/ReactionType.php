<?php

namespace TeleBot\System\Telegram\Types;

class ReactionType
{

    /** @var string $type reaction type */
    public string $type;

    /** @var string|null $emoji emoji (for type: emoji) */
    public ?string $emoji = null;

    /** @var string|null $customEmojiId custom emoji id (for type: custom_emoji) */
    public ?string $customEmojiId = null;

    /**
     * default constructor
     *
     * @param array $reaction
     */
    public function __construct(protected array $reaction)
    {
        $this->type = $this->reaction['type'];
        $this->emoji = $this->reaction['emoji'] ?? null;
        $this->customEmojiId = $this->reaction['customEmojiId'] ?? null;
    }

}