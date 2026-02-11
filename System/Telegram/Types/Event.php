<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Types;

use DateTime;
use Exception;
use ReflectionClass;
use TeleBot\System\Telegram\Traits\MapProp;
use TeleBot\System\Telegram\Support\Hydrator;

class Event
{

    /** @var string update id */
    #[MapProp('update_id')]
    public string $id;

    /** @var DateTime|null $date update date */
    public ?DateTime $date = null;

    /** @var Chat|null $chat event Chat */
    public ?Chat $chat = null;

    /** @var User|null $from event From */
    public ?User $from = null;

    /** @var IncomingMessage|null $message message update */
    #[MapProp('message', IncomingMessage::class)]
    public ?IncomingMessage $message = null;

    /** @var IncomingMessage|null $editedMessage edited message update */
    #[MapProp('edited_message', IncomingMessage::class)]
    public ?IncomingMessage $editedMessage = null;

    /** @var IncomingCallbackQuery|null $callbackQuery callback query update */
    #[MapProp('callback_query', IncomingCallbackQuery::class)]
    public ?IncomingCallbackQuery $callbackQuery = null;

    /** @var IncomingInlineQuery|null $inlineQuery inline query update */
    #[MapProp('inline_query', IncomingInlineQuery::class)]
    public ?IncomingInlineQuery $inlineQuery = null;

    /** @var IncomingChosenInlineResult|null $chosenInlineQuery chosen inline query update */
    #[MapProp('chosen_inline_result', IncomingChosenInlineResult::class)]
    public ?IncomingChosenInlineResult $chosenInlineQuery = null;

    /** @var IncomingChatMember|null $myChatMember my chat member update */
    #[MapProp('my_chat_member', IncomingChatMember::class)]
    public ?IncomingChatMember $myChatMember = null;

    /** @var IncomingChatMember|null $chatMember chat member update */
    #[MapProp('chat_member', IncomingChatMember::class)]
    public ?IncomingChatMember $chatMember = null;

    /** @var IncomingPreCheckoutQuery|null $preCheckoutQuery pre checkout query */
    #[MapProp('pre_checkout_query', IncomingPreCheckoutQuery::class)]
    public ?IncomingPreCheckoutQuery $preCheckoutQuery = null;

    /** @var IncomingShippingQuery|null $shippingQuery shipping query */
    #[MapProp('shipping_query', IncomingShippingQuery::class)]
    public ?IncomingShippingQuery $shippingQuery = null;

    /** @var IncomingMessage|null $channelPost channel post update */
    #[MapProp('channel_post', IncomingMessage::class)]
    public ?IncomingMessage $channelPost = null;

    /** @var IncomingMessage|null $editedChannelPost edited channel post update */
    #[MapProp('edited_channel_post', IncomingMessage::class)]
    public ?IncomingMessage $editedChannelPost = null;

    /** @var IncomingBusinessConnection|null $businessConnection business connection update */
    #[MapProp('business_connection', IncomingBusinessConnection::class)]
    public ?IncomingBusinessConnection $businessConnection = null;

    /** @var IncomingMessage|null $businessMessage business message update */
    #[MapProp('business_message', IncomingMessage::class)]
    public ?IncomingMessage $businessMessage = null;

    /** @var IncomingMessage|null $editedBusinessMessage edited business message update */
    #[MapProp('edited_business_message', IncomingMessage::class)]
    public ?IncomingMessage $editedBusinessMessage = null;

    /** @var IncomingBusinessMessagesDeleted|null $deletedBusinessMessages deleted business message update */
    #[MapProp('deleted_business_messages', IncomingBusinessMessagesDeleted::class)]
    public ?IncomingBusinessMessagesDeleted $deletedBusinessMessages = null;

    /** @var IncomingMessageReactionUpdated|null $messageReaction message reaction update */
    #[MapProp('message_reaction', IncomingMessageReactionUpdated::class)]
    public ?IncomingMessageReactionUpdated $messageReaction = null;

    /** @var IncomingMessageReactionCountUpdated|null $messageReactionCount message reaction count update */
    #[MapProp('message_reaction_count', IncomingMessageReactionCountUpdated::class)]
    public ?IncomingMessageReactionCountUpdated $messageReactionCount = null;

    /** @var IncomingPoll|null $poll poll update */
    #[MapProp('poll', IncomingPoll::class)]
    public ?IncomingPoll $poll = null;

    /** @var IncomingPollAnswer|null $pollAnswer poll answer update */
    #[MapProp('poll_answer', IncomingPollAnswer::class)]
    public ?IncomingPollAnswer $pollAnswer = null;

    /** @var IncomingChatJoinRequest|null $chatJoinRequest chat join request update */
    #[MapProp('chat_join_request', IncomingChatJoinRequest::class)]
    public ?IncomingChatJoinRequest $chatJoinRequest = null;

    /** @var IncomingChatBoostUpdated|null $chatBoost chat boost update */
    #[MapProp('chat_boost', IncomingChatBoostUpdated::class)]
    public ?IncomingChatBoostUpdated $chatBoost = null;

    /** @var IncomingChatBoostRemoved|null $removedChatBoost chat boost removed update */
    #[MapProp('removed_chat_boost', IncomingChatBoostRemoved::class)]
    public ?IncomingChatBoostRemoved $removedChatBoost = null;

    /**
     * default constructor
     *
     * @param array $event
     * @throws Exception
     */
    public function __construct(protected readonly array $event)
    {
        Hydrator::hydrate($this, $event);
        $this->setProps();
    }

    /**
     * set Chat and From values
     *
     * @return void
     */
    protected function setProps(): void
    {
        $skipProps = ['id', 'date'];
        $refClass = new ReflectionClass($this);
        foreach ($refClass->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isInitialized($this)
                && $property->getValue($this) !== null
                && !in_array($property->getName(), $skipProps)
            ) {
                $this->date = $property->getValue($this)->date ?? null;
                $this->from = $property->getValue($this)->from ?? null;
                $this->chat = $property->getValue($this)->chat ?? null;
                break;
            }
        }
    }
}