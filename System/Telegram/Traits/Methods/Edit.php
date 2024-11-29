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

use GuzzleHttp\Psr7\Utils;

trait Edit
{

    /**
     * edit message text
     *
     * @param string $messageId
     * @param string $text
     * @return bool
     */
    public function editMessageText(string $messageId, string $text): bool
    {
        $data = $this->post(__FUNCTION__, [
            'message_id' => $messageId,
            'text' => $text,
            ...($this->mode ? ['parse_mode' => $this->mode] : [])
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * edit media message
     *
     * @param string $messageId
     * @param string $type
     * @param string $mediaPath
     * @param string|null $caption
     * @param bool $asUrl
     * @return bool
     */
    public function editMessageMedia(
        string $messageId,
        string $type,
        string $mediaPath,
        string $caption = null,
        bool   $asUrl = false
    ): bool
    {
        $data = $this->post(__FUNCTION__, [
            'message_id' => $messageId,
            'media' => json_encode([
                'type' => $type,
                'caption' => $caption ?? '',
                ...($this->mode ? ['parse_mode' => $this->mode] : []),
                'media' => 'attach://media_file',
            ]),
            'media_file' => $asUrl ? $mediaPath : Utils::tryFopen($mediaPath, 'r'),
        ]);

        return $data && $data['ok'] == true;
    }

}