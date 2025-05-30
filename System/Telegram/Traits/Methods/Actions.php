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
use TeleBot\System\Telegram\Enums\ChatActions;
use TeleBot\System\Telegram\Traits\Extensions;

trait Actions
{

    /**
     * send an action
     *
     * @param ChatActions $action action to send
     * @return BotApi|Extensions
     */
    public function sendChatAction(ChatActions $action = ChatActions::TYPING): self
    {
        $this->post(__FUNCTION__, [
            'chat_id' => $this->chatId,
            'action' => $action->value
        ]);

        return $this;
    }

}