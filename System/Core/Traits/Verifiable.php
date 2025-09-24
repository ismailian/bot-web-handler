<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Core\Traits;

use TeleBot\System\Core\Bootstrap;

trait Verifiable
{

    /**
     * verify payload
     *
     * @return void
     */
    private function verifyPayload(): void
    {
        $payload = request()->json();
        $updates = [
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

        if (!isset($payload['update_id']) || empty(array_intersect($updates, array_keys($payload)))) {
            response()->setStatusCode(401)->end();
        }
    }

    /**
     * verify request route
     *
     * @return Verifiable|Bootstrap
     */
    private function verifyRoute(): self
    {
        if (!empty(($routes = self::$config['routes']))) {
            if (!empty($routes['telegram'])) {
                if (!in_array(request()->uri(), $routes['telegram'])) {
                    response()->setStatusCode(401)->end();
                }
            }
        }

        return $this;
    }

    /**
     * verify request signature
     *
     * @return Verifiable|Bootstrap
     */
    private function verifySignature(): self
    {
        if (!empty(($signature = self::$config['signature']))) {
            $value = request()->header('X-Telegram-Bot-Api-Secret-Token');
            if (empty($value) || !hash_equals($signature, $value)) {
                response()->setStatusCode(401)->end();
            }
        }

        return $this;
    }

    /**
     * verify source IP address
     *
     * @return Verifiable|Bootstrap
     */
    private function verifyIP(): self
    {
        if (!empty(($sourceIp = self::$config['ip']))) {
            if (!hash_equals($sourceIp, request()->ip())) {
                response()->setStatusCode(401)->end();
            }
        }

        return $this;
    }

    /**
     * verify user id
     *
     * @return bool
     */
    private function verifyUserId(): bool
    {
        $payload = request()->json();
        unset($payload['update_id']);
        $keys = array_keys($payload);
        $userId = $payload[$keys[0]]['from']['id'];

        $whitelist = self::$config['users']['whitelist'];
        $blacklist = self::$config['users']['blacklist'];

        if (!empty($whitelist)) return in_array($userId, $whitelist);
        if (!empty($blacklist)) return !in_array($userId, $blacklist);

        return true;
    }

}