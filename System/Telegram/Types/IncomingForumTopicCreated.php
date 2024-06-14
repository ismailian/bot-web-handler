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

class IncomingForumTopicCreated
{

    /** @var string $name name of the topic */
    public string $name;

    /** @var int $iconColor Color of the topic icon in RGB format */
    public int $iconColor;

    /** @var string|null $iconCustomEmojiId Unique identifier of the custom emoji shown as the topic icon */
    public ?string $iconCustomEmojiId = null;

    /**
     * default constructor
     *
     * @param array $incomingForumTopicCreated
     */
    public function __construct(protected array $incomingForumTopicCreated)
    {
        $this->name = $this->incomingForumTopicCreated['name'];
        $this->iconColor = $this->incomingForumTopicCreated['icon_color'];
        if (array_key_exists('icon_custom_emoji_id', $this->incomingForumTopicCreated['bot_topic'])) {
            $this->iconCustomEmojiId = $this->incomingForumTopicCreated['bot_topic']['icon_custom_emoji_id'];
        }
    }

}