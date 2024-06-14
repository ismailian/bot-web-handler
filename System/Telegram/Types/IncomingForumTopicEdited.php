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

class IncomingForumTopicEdited
{

    /** @var string|null $name New name of the topic, if it was edited */
    public ?string $name = null;

    /** @var string|null $iconCustomEmojiId
     * New identifier of the custom emoji shown as the topic icon, if it was edited; an empty string if the icon was removed
     */
    public ?string $iconCustomEmojiId = null;

    /**
     * default constructor
     *
     * @param array $incomingForumTopicEdited
     */
    public function __construct(protected readonly array $incomingForumTopicEdited)
    {
        $this->name = $this->incomingForumTopicEdited['name'] ?? null;
        $this->iconCustomEmojiId = $this->incomingForumTopicEdited['icon_custom_emoji_id'] ?? null;
    }

}