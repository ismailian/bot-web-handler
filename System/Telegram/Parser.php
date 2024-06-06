<?php

namespace TeleBot\System\Telegram;

class Parser
{

    /** @var array|string[] list of top level updates */
    static array $updates = [
        'message', 'edited_message', 'callback_query',
        'inline_query', 'chosen_inline_result',
        'shipping_query', 'pre_checkout_query',
        'channel_post', 'edited_channel_post',
        'poll', 'poll_answer',
        'my_chat_member', 'chat_member', 'chat_join_request',
        'business_connection', 'business_message',
        'edited_business_message', 'deleted_business_messages',
        'message_reaction', 'message_reaction_count',
        'chat_boost', 'removed_chat_boost'
    ];

    /** @var array list of message types */
    static array $messageTypes = [
        'text', 'photo', 'video', 'video_note', 'audio', 'voice',
        'animation', 'document', 'contact', 'location',
        'poll', 'dice', 'sticker', 'game',
        'successful_payment'
    ];

}