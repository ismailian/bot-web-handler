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

interface ISessionDriver
{

    /**
     * default constructor
     *
     * @param string $sessionId
     */
    public function __construct(string $sessionId);

    /**
     * read all available cache data
     *
     * @param int $cursor redis cursor (Redis only)
     * @param int $count max records to return (Redis only)
     * @return array
     */
    public function getAll(int $cursor = 0, int $count = 100): array;

    /**
     * read session
     *
     * @return array
     */
    public function read(): array;

    /**
     * write session data
     *
     * @param array $data session data
     * @param string|null $ttl cache TTL in ISO-8601 format (e.g: PT24H)
     * @return bool True on success, otherwise, False
     */
    public function write(array $data, ?string $ttl = null): bool;

    /**
     * delete session entry
     *
     * @return int|bool
     */
    public function delete(): int|bool;

}