<?php

namespace TeleBot\System\Telegram\Types;

use DateTime;
use Exception;

class Event
{

    /** @var string update id */
    public string $id;

    /** @var DateTime|null $date update date */
    public ?DateTime $date = null;

    /** @var Chat|null $chat event Chat */
    public ?Chat $chat = null;

    /** @var From|null $from event From */
    public ?From $from = null;

    /** @var Message|null $message message event */
    public ?Message $message = null;

    /** @var object|null $callbackQuery callback query event */
    public ?object $callbackQuery = null;

    /** @var InlineQuery|null $inlineQuery inline query event */
    public ?InlineQuery $inlineQuery = null;

    /** @var object|null $chosenInlineQuery chosen inline query event */
    public ?object $chosenInlineQuery = null;

    /** @var ChatMember|null $myChatMember my chat member updated */
    public ?ChatMember $myChatMember = null;

    /** @var ChatMember|null $chatMember chat member updated */
    public ?ChatMember $chatMember = null;

    /** @var PreCheckoutQuery|null $preCheckoutQuery pre checkout query */
    public ?PreCheckoutQuery $preCheckoutQuery = null;

    /**
     * default constructor
     *
     * @param array $event
     * @throws Exception
     */
    public function __construct(protected array $event)
    {
        $this->id = $this->event['update_id'];
        unset($this->event['update_id']);

        /** <Message | EditedMessage> */
        if (array_intersect(['message', 'edited_message'], array_keys($this->event))) {
            $this->message = new Message($this->event['message']);
        }

        /** <CallbackQuery> */
        if (array_key_exists('callback_query', $this->event)) {
            $this->callbackQuery = new CallbackQuery($this->event['callback_query']);
        }

        /** <InlineQuery> */
        if (array_key_exists('inline_query', $this->event)) {
            $this->inlineQuery = new InlineQuery($this->event['inline_query']);
        }

        /** <ChosenInlineQuery> */
        if (array_key_exists('chosen_inline_query', $this->event)) {
            $this->chosenInlineQuery = null;
        }

        /** <MyChatMember> */
        if (array_key_exists('my_chat_member', $this->event)) {
            $this->myChatMember = new ChatMember($this->event['my_chat_member']);
        }

        /** <ChatMember> */
        if (array_key_exists('chat_member', $this->event)) {
            $this->chatMember = new ChatMember($this->event['chat_member']);
        }

        /** <PreCheckoutQuery> */
        if (array_key_exists('pre_checkout_query', $this->event)) {
            $this->preCheckoutQuery = new PreCheckoutQuery($this->event['pre_checkout_query']);
        }

        $this->setProps(match (array_keys($this->event)[0]) {
            'message' => $this->message,
            'callback_query' => $this->callbackQuery,
            'inline_query' => $this->inlineQuery,
            'chosen_inline_query' => $this->chosenInlineQuery,
            'my_chat_member' => $this->myChatMember,
            'chat_member' => $this->chatMember,
            'pre_checkout_query' => $this->preCheckoutQuery,
        });
    }

    /**
     * set Chat and From values
     *
     * @param Message|CallbackQuery|InlineQuery|ChatMember $update
     * @return void
     */
    protected function setProps(Message|CallbackQuery|InlineQuery|ChatMember $update): void
    {
        $this->date ??= $update->date;
        $this->from = $update?->from;
        $this->chat = $update?->chat;
    }
}