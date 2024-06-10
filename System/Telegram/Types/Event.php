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

    /** @var User|null $from event From */
    public ?User $from = null;

    /** @var IncomingMessage|null $message message update */
    public ?IncomingMessage $message = null;

    /** @var IncomingMessage|null $editedMessage edited message update */
    public ?IncomingMessage $editedMessage = null;

    /** @var IncomingCallbackQuery|null $callbackQuery callback query update */
    public ?IncomingCallbackQuery $callbackQuery = null;

    /** @var IncomingInlineQuery|null $inlineQuery inline query update */
    public ?IncomingInlineQuery $inlineQuery = null;

    /** @var IncomingChosenInlineResult|null $chosenInlineQuery chosen inline query update */
    public ?IncomingChosenInlineResult $chosenInlineQuery = null;

    /** @var IncomingChatMember|null $myChatMember my chat member update */
    public ?IncomingChatMember $myChatMember = null;

    /** @var IncomingChatMember|null $chatMember chat member update */
    public ?IncomingChatMember $chatMember = null;

    /** @var IncomingPreCheckoutQuery|null $preCheckoutQuery pre checkout query */
    public ?IncomingPreCheckoutQuery $preCheckoutQuery = null;

    /** @var IncomingShippingQuery|null $shippingQuery shipping query */
    public ?IncomingShippingQuery $shippingQuery = null;

    /** @var IncomingMessage|null $channelPost channel post update */
    public ?IncomingMessage $channelPost = null;

    /** @var IncomingMessage|null $editedChannelPost edited channel post update */
    public ?IncomingMessage $editedChannelPost = null;

    /** @var IncomingBusinessConnection|null $businessConnection business connection update */
    public ?IncomingBusinessConnection $businessConnection = null;

    /** @var IncomingMessage|null $businessMessage business message update */
    public ?IncomingMessage $businessMessage = null;

    /** @var IncomingMessage|null $editedBusinessMessage edited business message update */
    public ?IncomingMessage $editedBusinessMessage = null;

    /** @var IncomingBusinessMessagesDeleted|null $deletedBusinessMessages deleted business message update */
    public ?IncomingBusinessMessagesDeleted $deletedBusinessMessages = null;

    /** @var IncomingMessageReactionUpdated|null $messageReaction message reaction update */
    public ?IncomingMessageReactionUpdated $messageReaction = null;

    /** @var IncomingMessageReactionCountUpdated|null $messageReactionCount message reaction count update */
    public ?IncomingMessageReactionCountUpdated $messageReactionCount = null;

    /** @var IncomingPoll|null $poll poll update */
    public ?IncomingPoll $poll = null;

    /** @var IncomingPollAnswer|null $pollAnswer poll answer update */
    public ?IncomingPollAnswer $pollAnswer = null;

    /** @var IncomingChatJoinRequest|null $chatJoinRequest chat join request update */
    public ?IncomingChatJoinRequest $chatJoinRequest = null;

    /** @var IncomingChatBoostUpdated|null $chatBoost chat boost update */
    public ?IncomingChatBoostUpdated $chatBoost = null;

    /** @var IncomingChatBoostRemoved|null $removedChatBoost chat boost removed update */
    public ?IncomingChatBoostRemoved $removedChatBoost = null;

    /**
     * default constructor
     *
     * @param array $event
     * @throws Exception
     */
    public function __construct(protected readonly array $event)
    {
        $this->id = $this->event['update_id'];

        /**
         * <Message>
         * <EditedMessage>
         * <ChannelPost>
         * <EditedChannelPost>
         * <BusinessMessage>
         * <EditedBusinessMessage>
         */
        $messages = [
            'message', 'edited_message',
            'channel_post', 'edited_channel_post',
            'business_message', 'edited_business_message',
        ];
        if (!empty($result = array_intersect($messages, array_keys($this->event)))) {
            $result = array_values($result);
            $update = new IncomingMessage($this->event[$result[0]]);
            switch ($result[0]) {
                case 'message': $this->message = $update; break;
                case 'edited_message': $this->editedMessage = $update; break;
                case 'channel_post': $this->channelPost = $update; break;
                case 'edited_channel_post': $this->editedChannelPost = $update; break;
                case 'business_message': $this->businessMessage = $update; break;
                case 'edited_business_message': $this->editedBusinessMessage = $update; break;
            }

            $this->setProps($update);
        }

        /** <CallbackQuery> */
        if (array_key_exists('callback_query', $this->event)) {
            $this->callbackQuery = new IncomingCallbackQuery($this->event['callback_query']);
            $this->setProps($this->callbackQuery);
        }

        /** <InlineQuery> */
        if (array_key_exists('inline_query', $this->event)) {
            $this->inlineQuery = new IncomingInlineQuery($this->event['inline_query']);
            $this->setProps($this->inlineQuery);
        }

        /** <ChosenInlineResult> */
        if (array_key_exists('chosen_inline_result', $this->event)) {
            $this->chosenInlineQuery = new IncomingChosenInlineResult($this->event['chosen_inline_result']);
            $this->setProps($this->chosenInlineQuery);
        }

        /**
         * <ChatMember>
         * <MyChatMember>
         */
        if (!empty($result = array_intersect(['chat_member', 'my_chat_member'], array_keys($this->event)))) {
            $result = array_values($result);
            $update = new IncomingChatMember($this->event[$result[0]]);
            switch ($result[0]) {
                case 'chat_member': $this->chatMember = $update; break;
                case 'my_chat_member': $this->myChatMember = $update; break;
            }

            $this->setProps($update);
        }

        /** <PreCheckoutQuery> */
        if (array_key_exists('pre_checkout_query', $this->event)) {
            $this->preCheckoutQuery = new IncomingPreCheckoutQuery($this->event['pre_checkout_query']);
            $this->setProps($this->preCheckoutQuery);
        }

        /** <BusinessConnection> */
        if (array_key_exists('business_connection', $this->event)) {
            $this->businessConnection = new IncomingBusinessConnection($this->event['business_connection']);
            $this->setProps($this->businessConnection);
        }

        /** <ShippingQuery> */
        if (array_key_exists('shipping_query', $this->event)) {
            $this->shippingQuery = new IncomingShippingQuery($this->event['shipping_query']);
            $this->setProps($this->shippingQuery);
        }

        /** <Poll> */
        if (array_key_exists('poll', $this->event)) {
            $this->poll = new IncomingPoll($this->event['poll']);
            $this->setProps($this->poll);
        }

        /** <PollAnswer> */
        if (array_key_exists('poll_answer', $this->event)) {
            $this->pollAnswer = new IncomingPollAnswer($this->event['poll_answer']);
            $this->setProps($this->pollAnswer);
        }

        /** <ChatJoinRequest> */
        if (array_key_exists('chat_join_request', $this->event)) {
            $this->chatJoinRequest = new IncomingChatJoinRequest($this->event['chat_join_request']);
            $this->setProps($this->chatJoinRequest);
        }

        /** <ChatBoost> */
        if (array_key_exists('chat_boost', $this->event)) {
            $this->chatBoost = new IncomingChatBoostUpdated($this->event['chat_boost']);
            $this->setProps($this->chatBoost);
        }

        /** <RemovedChatBoost> */
        if (array_key_exists('removed_chat_boost', $this->event)) {
            $this->removedChatBoost = new IncomingChatBoostRemoved($this->event['removed_chat_boost']);
            $this->setProps($this->removedChatBoost);
        }

        /** <MessageReaction> */
        if (array_key_exists('message_reaction', $this->event)) {
            $this->messageReaction = new IncomingMessageReactionUpdated($this->event['message_reaction']);
            $this->setProps($this->messageReaction);
        }

        /** <MessageReactionCount> */
        if (array_key_exists('message_reaction_count', $this->event)) {
            $this->messageReactionCount = new IncomingMessageReactionCountUpdated($this->event['message_reaction_count']);
            $this->setProps($this->messageReactionCount);
        }

        /** <DeletedBusinessMessage> */
        if (array_key_exists('deleted_business_messages', $this->event)) {
            $this->deletedBusinessMessages = new IncomingBusinessMessagesDeleted($this->event['deleted_business_messages']);
            $this->setProps($this->deletedBusinessMessages);
        }
    }

    /**
     * set Chat and From values
     *
     * @param mixed $update
     * @return void
     */
    protected function setProps(mixed $update): void
    {
        $this->date = $update->date ?? null;
        $this->from = $update?->from ?? null;
        $this->chat = $update?->chat ?? null;
    }
}