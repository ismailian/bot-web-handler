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

use TeleBot\System\Telegram\BotApi;
use TeleBot\System\Telegram\Traits\Extensions;

trait Actions
{

    const TYPING = 'typing';
    const UPLOAD_PHOTO = 'upload_photo';
    const UPLOAD_VIDEO = 'upload_video';
    const RECORD_VIDEO = 'record_video';
    const RECORD_VIDEO_NOTE = 'record_video_note';
    const UPLOAD_VIDEO_NOTE = 'upload_video_note';
    const RECORD_VOICE = 'record_video';
    const UPLOAD_VOICE = 'upload_voice';
    const UPLOAD_DOCUMENT = 'upload_document';
    const CHOOSE_STICKER = 'choose_sticker';
    const FIND_LOCATION = 'find_location';

    /**
     * send an action
     *
     * @param string $action action to send
     * @return BotApi|Extensions
     */
    public function sendChatAction(string $action): self
    {
        $this->post(__FUNCTION__, [
            'chat_id' => $this->chatId,
            'action' => $action
        ]);

        return $this;
    }

}