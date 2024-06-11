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
    public function __construct(protected readonly array $reaction)
    {
        $this->type = $this->reaction['type'];
        $this->emoji = $this->reaction['emoji'] ?? null;
        $this->customEmojiId = $this->reaction['customEmojiId'] ?? null;
    }

}