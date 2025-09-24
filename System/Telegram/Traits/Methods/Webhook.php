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

trait Webhook
{

    /**
     * set bot webhook
     *
     * @param string $webhookUrl webhook url
     * @param string|null $secretToken secret token (optional)
     * @return bool
     */
    public function setWebhook(string $webhookUrl, ?string $secretToken = null): bool
    {
        $data = $this->post(__FUNCTION__, [
            'url' => $webhookUrl,
            ...($secretToken ? ['secret_token' => $secretToken] : [])
        ]);

        return $data && $data['ok'] == true;
    }

    /**
     * delete bot webhook
     *
     * @return bool
     */
    public function deleteWebhook(): bool
    {
        $data = $this->post(__FUNCTION__, []);

        return $data && $data['ok'] == true;
    }

}