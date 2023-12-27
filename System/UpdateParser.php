<?php

namespace TeleBot\System;

use TeleBot\System\Exceptions\InvalidUpdate;
use TeleBot\System\Exceptions\InvalidMessage;

class UpdateParser
{

    /**
     * parse telegram update
     *
     * @param array $data
     * @return array
     * @throws InvalidUpdate|InvalidMessage
     */
    public static function parseUpdate(array $data): array
    {
        $event = ['data' => $data];

        /** get update id */
        $event['event_id'] = $data['update_id'] ?? null;
        unset($data['update_id']);

        /** get top level update type */
        $keys = array_keys($data);
        $topLevelEventName = $keys[0];
        $topLevelUpdates = [
            'message', 'edited_message', 'callback_query',
            'inline_query', 'chosen_inline_result',
            'shipping_query', 'pre_checkout_query',
            'channel_post', 'edited_channel_post',
            'poll', 'poll_answer',
            'my_chat_member', 'chat_member', 'chat_join_request'
        ];

        if (empty($topLevelEventName) || !in_array($topLevelEventName, $topLevelUpdates))
            throw new InvalidUpdate('Unrecognized update type: ' . $topLevelEventName);

        $event['type'] = str_replace('_', '', ucwords($topLevelEventName, '_')) . '::class';

        /** check for message types */
        if (in_array($topLevelEventName, ['message', 'edited_message'])) {
            $data[$topLevelEventName] = self::parseMessage($data[$topLevelEventName]);
            $event['type'] = $data[$topLevelEventName]['type'];
        }

        return $event;
    }

    /**
     * parse message
     *
     * @param array $data
     * @return array
     * @throws InvalidMessage
     */
    public static function parseMessage(array $data): array
    {
        $message = ['data' => $data];

        /** get static properties */
        $message['id'] = $data['message_id'] ?? null;
        $message['date'] = $data['date'] ?? null;
        $message['from'] = $data['from'] ?? null;
        $message['chat'] = $data['chat'] ?? null;
        $message['caption'] = $data['caption'] ?? null;

        unset($data['message_id'], $data['date'], $data['from'], $data['chat'], $data['caption']);

        /** determine message type */
        $keys = array_keys($data);
        $typeName = $keys[0];
        $messageTypes = [
            'text', 'photo', 'video', 'audio', 'voice',
            'animation', 'document', 'contact', 'location',
            'poll', 'dice', 'sticker', 'game'
        ];

        if (empty($typeName) || !in_array($typeName, $messageTypes)) {
            throw new InvalidMessage('Unrecognized message type: ' . $typeName);
        }

        $message['type'] = str_replace('_', '', ucwords($typeName, '_')) . '::class';
        return $message;
    }

}