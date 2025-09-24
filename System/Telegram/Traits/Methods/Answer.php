<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Traits\Methods;

trait Answer
{

    /**
     * answer a pre-checkout query
     *
     * @param string $queryId
     * @param bool $ok
     * @param string|null $errorMessage
     * @return bool
     */
    public function answerPreCheckoutQuery(string $queryId, bool $ok, ?string $errorMessage = null): bool
    {
        $data = $this->post(__FUNCTION__, [
            'ok' => $ok,
            'pre_checkout_query_id' => $queryId,
            ...($errorMessage ? ['error_message' => $errorMessage] : [])
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * answer callback query
     *
     * @param string $callbackQueryId callback query id
     * @param string|null $text notification text to show to the user
     * @param bool $showAlert show an alert instead of a notification
     * @param string|null $url url to be opened by the user (only for callback_game type)
     * @param int $cacheTime time - in seconds - to cache the result by the user's app
     * @return bool
     */
    public function answerCallbackQuery(
        string  $callbackQueryId,
        ?string $text = null,
        bool    $showAlert = false,
        ?string $url = null,
        int     $cacheTime = 0
    ): bool
    {
        $data = $this->post(__FUNCTION__, [
            'callback_query_id' => $callbackQueryId,
            ...($text ? ['text' => $text] : []),
            ...($showAlert ? ['show_alert' => $showAlert] : []),
            ...($url ? ['url' => $url] : []),
            ...($cacheTime ? ['cache_time' => $cacheTime] : [])
        ]);

        return $data && $data['ok'] == true;
    }

}