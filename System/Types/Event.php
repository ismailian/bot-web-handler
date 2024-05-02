<?php

namespace TeleBot\System\Types;

class Event
{

    /** @var string update id */
    public string $id;

    /** @var Message|null $message message event */
    public ?Message $message = null;

    /** @var object|null $callbackQuery callback query event */
    public ?object $callbackQuery = null;

    /** @var object|null $inlineQuery inline query event */
    public ?object $inlineQuery = null;

    /** @var object|null $chosenInlineQuery chosen inline query event */
    public ?object $chosenInlineQuery = null;

    /**
     * default constructor
     *
     * @param array $event
     */
    public function __construct(protected array $event)
    {
        /** <Message | EditedMessage> */
        if (array_intersect(['message', 'edited_message'], array_keys($this->event))) {
            $this->message = new Message($this->event['message']);
        }

        /** <CallbackQuery> */
        if (in_array('callback_query', $this->event)) {
            $this->callbackQuery = new CallbackQuery($this->event['callback_query']);
        }

        /** <InlineQuery> */
        if (in_array('inline_query', $this->event)) {
            $this->inlineQuery = null;
        }

        /** <ChosenInlineQuery> */
        if (in_array('chosen_inline_query', $this->event)) {
            $this->chosenInlineQuery = null;
        }
    }
}