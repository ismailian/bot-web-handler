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

interface IFile
{

    /**
     * get file temporary link
     *
     * @param string|int $idOrIndex
     * @return string|null
     */
    public function getLink(string|int $idOrIndex): ?string;

    /**
     * download context file
     *
     * @param string|null $filename name of the file, defaults to remote filename
     * @return string|null
     */
    public function saveAs(string $filename = null): ?string;

    /**
     * get file size
     *
     * @param bool $readable
     * @return int|string
     */
    public function getSize(bool $readable = false): int|string;

}