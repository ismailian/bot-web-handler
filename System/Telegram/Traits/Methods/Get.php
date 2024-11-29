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

use TeleBot\System\Telegram\Types\User;

trait Get
{

    /**
     * get bot info
     *
     * @return User|null
     */
    public function getMe(): ?User
    {
        $data = $this->get(__FUNCTION__);
        if (empty($data) || !array_key_exists('user', $data['result'])) return null;

        return new User($data['result']['user']);
    }

    /**
     * get user info
     *
     * @param string $userId
     * @return User|null
     */
    public function getChatMember(string $userId): ?User
    {
        $data = $this->get(__FUNCTION__, ['user_id' => $userId]);
        if (empty($data) || !array_key_exists('user', $data['result'])) return null;

        return new User($data['result']['user']);
    }

    /**
     * get updates
     *
     * @param int|null $offset
     * @param int|null $limit
     * @param int|null $timeout
     * @param array $allowedUpdated
     * @return array
     */
    public function getUpdates(
        int   $offset = null,
        int   $limit = null,
        int   $timeout = null,
        array $allowedUpdated = []
    ): array
    {
        $data = $this->get(__FUNCTION__, [
            ...($offset ? ['offset' => $offset] : []),
            ...($limit ? ['limit' => $limit] : []),
            ...($timeout ? ['timeout' => $timeout] : []),
            ...($allowedUpdated ? ['allowed_updates' => $allowedUpdated] : [])
        ]);

        if (empty($data) || !array_key_exists('result', $data))
            return [];

        return $data['result'];
    }

}