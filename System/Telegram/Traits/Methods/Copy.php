<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Traits\Methods;

use Exception;
use TeleBot\System\Telegram\Types\MessageId;
use TeleBot\System\Telegram\Support\EntityBuilder;

trait Copy
{

    /**
     * copy a message
     *
     * @param string $chatId
     * @param string $messageId
     * @param string|null $fromChatId
     * @param string|null $messageThreadId
     * @param string|null $caption
     * @param EntityBuilder|null $captionEntities
     * @param bool|null $protectContent
     * @param bool|null $allowPaidBroadcast
     * @param bool|null $disableNotification
     * @param bool|null $showCaptionAboveMedia
     * @return MessageId|bool
     * @throws Exception
     */
    public function copyMessage(
        string         $chatId,
        string         $messageId,
        ?string        $fromChatId = null,
        ?string        $messageThreadId = null,
        ?string        $caption = null,
        ?EntityBuilder $captionEntities = null,
        bool           $protectContent = false,
        bool           $allowPaidBroadcast = false,
        bool           $disableNotification = false,
        bool           $showCaptionAboveMedia = false,
    ): MessageId|bool
    {
        $data = $this->post(__FUNCTION__, [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'protect_content' => $protectContent,
            'message_thread_id' => $messageThreadId,
            'allow_paid_broadcast' => $allowPaidBroadcast,
            'disable_notification' => $disableNotification,
            'from_chat_id' => $fromChatId ?? event()?->from->id,
            'show_caption_above_media' => $showCaptionAboveMedia,
            ...($caption ? ['caption' => $caption] : []),
            ...($this->mode ? ['parse_mode' => $this->mode] : []),
            ...($captionEntities ? ['caption_entities' => $captionEntities->toArray()] : []),
        ]);

        if (!$data || !array_key_exists('result', $data)) {
            return false;
        }

        return new MessageId($data['result']);
    }

    /**
     * copy messages
     *
     * @param string $chatId
     * @param array $messageIds
     * @param string|null $fromChatId
     * @param string|null $messageThreadId
     * @param bool|null $protectContent
     * @param bool|null $disableNotification
     * @param bool $removeCaption
     * @return MessageId[]|bool
     * @throws Exception
     */
    public function copyMessages(
        string  $chatId,
        array   $messageIds,
        ?string $fromChatId = null,
        ?string $messageThreadId = null,
        bool    $protectContent = false,
        bool    $disableNotification = false,
        bool    $removeCaption = false,
    ): array|bool
    {
        $data = $this->post(__FUNCTION__, [
            'chat_id' => $chatId,
            'message_ids' => $messageIds,
            'protect_content' => $protectContent,
            'message_thread_id' => $messageThreadId,
            'disable_notification' => $disableNotification,
            'from_chat_id' => $fromChatId ?? event()?->from->id,
            'remove_caption' => $removeCaption
        ]);

        if (!$data || !array_key_exists('result', $data)) {
            return false;
        }

        return array_map(fn($m) => new MessageId($m), $data['result']);
    }

}