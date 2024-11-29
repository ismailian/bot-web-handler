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

trait Delete
{

    /**
     * delete a message
     *
     * @param string $messageId id of a message to delete
     * @return bool
     */
    public function deleteMessage(string $messageId): bool
    {
        $data = $this->post(__FUNCTION__, [
            'message_id' => $messageId
        ]);

        return $data && $data['ok'] == true;
    }

}