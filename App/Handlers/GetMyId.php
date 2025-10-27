<?php

namespace TeleBot\App\Handlers;

use TeleBot\System\IncomingEvent;
use TeleBot\System\Telegram\Filters\Chat;
use TeleBot\System\Telegram\Enums\InlineChatType;
use TeleBot\System\Telegram\Types\IncomingMessage;
use TeleBot\System\Telegram\Events\Messages\{Text, Mention};

class GetMyId extends IncomingEvent
{

    /**
     * handle all incoming private messages
     *
     * @return void
     */
    #[Text]
    #[Chat(InlineChatType::PRIVATE)]
    public function inPrivate(): void
    {
        $reply = "Your user ID: <strong>{$this->event->from->id}</strong>\n";
        $reply .= "Current chat ID: <strong>{$this->event->chat->id}</strong>";

        $this->telegram->sendMessage($reply);
    }

    /**
     * handle mentions in groups/supergroups
     *
     * @param IncomingMessage $message
     * @return void
     */
    #[Text]
    #[Mention('bot')]
    #[Chat(InlineChatType::SUPERGROUP)]
    public function inGroups(IncomingMessage $message): void
    {
        $reply = "Your user ID: <strong>{$this->event->from->id}</strong>\n";
        $reply .= "Current chat ID: <strong>{$this->event->chat->id}</strong>";

        // we want to reply to the exact message that mentioned the bot
        $message->replyWithText($reply);
    }

}