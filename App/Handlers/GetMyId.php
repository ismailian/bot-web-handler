<?php

namespace TeleBot\App\Handlers;

use Exception;
use TeleBot\System\IncomingEvent;
use GuzzleHttp\Exception\GuzzleException;
use TeleBot\System\Telegram\Enums\InlineChatType;
use TeleBot\System\Telegram\Filters\Chat;
use TeleBot\System\Telegram\Events\Messages\Text;
use TeleBot\System\Telegram\Types\IncomingMessage;
use TeleBot\System\Telegram\Events\Messages\Mention;

class GetMyId extends IncomingEvent
{

    /**
     * handle all incoming private messages
     *
     * @return void
     * @throws GuzzleException
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
     * @throws GuzzleException
     */
    #[Text]
    #[Mention('me')]
    #[Chat(InlineChatType::SUPERGROUP)]
    public function inGroups(IncomingMessage $message): void
    {
        $reply = "Your user ID: <strong>{$this->event->from->id}</strong>\n";
        $reply .= "Current chat ID: <strong>{$this->event->chat->id}</strong>";

        // we want to reply to the exact message that mentioned the bot
        $this->telegram->replyTo($message->id)->sendMessage($reply);
    }

}