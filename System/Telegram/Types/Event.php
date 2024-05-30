<?php

namespace TeleBot\System\Telegram\Types;

use Exception;

class Event
{

    /** @var string update id */
    public string $id;

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

    /** @var MyChatMember|null $myChatMember context chat member event */
    public ?MyChatMember $myChatMember = null;

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
            $this->myChatMember = new MyChatMember($this->event['my_chat_member']);
        }

        $this->setProps(match (array_keys($this->event)[0]) {
            'message' => $this->message,
            'callback_query' => $this->callbackQuery,
            'inline_query' => $this->inlineQuery,
            'chosen_inline_query' => $this->chosenInlineQuery,
            'my_chat_member' => $this->myChatMember,
        });
    }

    /**
     * set Chat and From values
     *
     * @param Message|CallbackQuery|InlineQuery|MyChatMember $update
     * @return void
     */
    protected function setProps(Message|CallbackQuery|InlineQuery|MyChatMember $update): void
    {
        $this->from = $update?->from;
        $this->chat = $update?->chat;
    }
}