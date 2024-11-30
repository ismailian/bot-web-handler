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

trait Session
{

    /**
     * close bot instance on a local server
     *
     * @return bool
     */
    public function close(): bool
    {
        $data = $this->get(__FUNCTION__);

        return $data['ok'] ?? false;
    }

    /**
     * logout from the cloud bot api
     *
     * @return bool
     */
    public function logOut(): bool
    {
        $data = $this->get(__FUNCTION__);

        return $data['ok'] ?? false;
    }

}