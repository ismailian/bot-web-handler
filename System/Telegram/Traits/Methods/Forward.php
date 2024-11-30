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
use TeleBot\System\Telegram\Types\IncomingMessage;

trait Forward
{

    /**
     * forward message
     *
     * @param string $chatId target chat to forward message to
     * @param string $messageId message id to forward
     * @param string|null $fromChatId source chat id. leave empty to use context chat
     * @param string|null $messageThreadId message thread id
     * @param bool|null $disableNotification set to true to disable notification
     * @param bool|null $protectContent set to true to protect message content
     * @return IncomingMessage|bool returns IncomingMessage on success, false on failure
     * @throws Exception
     */
    public function forwardMessage(
        string $chatId,
        string $messageId,
        string $fromChatId = null,
        string $messageThreadId = null,
        bool   $disableNotification = null,
        bool   $protectContent = null,
    ): IncomingMessage|bool
    {
        $data = $this->post(__FUNCTION__, [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'from_chat_id' => $fromChatId ?? event()?->from->id,
            'message_thread_id' => $messageThreadId,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent
        ]);

        if (!$data || !array_key_exists('result', $data)) {
            return false;
        }

        return event()->message;
    }

    /**
     * forward messages
     *
     * @param string $chatId target chat to forward message to
     * @param array $messageIds message ids to forward
     * @param string|null $fromChatId source chat id. leave empty to use context chat
     * @param string|null $messageThreadId message thread id
     * @param bool|null $disableNotification set to true to disable notification
     * @param bool|null $protectContent set to true to protect message content
     * @return MessageId[]|bool returns IncomingMessage on success, false on failure
     * @throws Exception
     */
    public function forwardMessages(
        string $chatId,
        array  $messageIds,
        string $fromChatId = null,
        string $messageThreadId = null,
        bool   $disableNotification = null,
        bool   $protectContent = null,
    ): array|bool
    {
        $data = $this->post(__FUNCTION__, [
            'chat_id' => $chatId,
            'message_ids' => $messageIds,
            'from_chat_id' => $fromChatId ?? event()?->from->id,
            'message_thread_id' => $messageThreadId,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent
        ]);

        if (!$data || !array_key_exists('result', $data)) {
            return false;
        }

        return array_map(fn($mi) => new MessageId($mi), $data['result']);
    }

}