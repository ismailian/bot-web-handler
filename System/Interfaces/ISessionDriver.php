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
     * read session
     *
     * @return array
     */
    public function read(): array;

    /**
     * write session data
     *
     * @param array $data
     * @return bool
     */
    public function write(array $data): bool;

    /**
     * delete session entry
     *
     * @return int|bool
     */
    public function delete(): int|bool;

}