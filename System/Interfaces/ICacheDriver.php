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
     * read all available cache data
     *
     * @param int $cursor redis cursor (Redis only)
     * @param int $count max records to return (Redis only)
     * @return array
     */
    public function getAll(int $cursor = 0, int $count = 100): array;

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
     * @param string|null $ttl cache TTL in ISO-8601 format (e.g: PT24H)
     * @return bool
     */
    public function write(string $key, mixed $data, ?string $ttl = null): bool;

    /**
     * delete cache data
     *
     * @param string $key cache key
     * @return bool
     */
    public function delete(string $key): bool;

}