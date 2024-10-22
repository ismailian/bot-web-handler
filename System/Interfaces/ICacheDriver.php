<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Interfaces;

interface ICacheDriver
{

    /**
     * read cache data
     *
     * @param string $key cache key
     * @return array
     */
    public function read(string $key): mixed;

    /**
     * write cache data
     *
     * @param string $key cache key
     * @param mixed $data cache data
     * @return bool
     */
    public function write(string $key, mixed $data): bool;

    /**
     * delete cache data
     *
     * @param string $key cache key
     * @return bool
     */
    public function delete(string $key): bool;

}