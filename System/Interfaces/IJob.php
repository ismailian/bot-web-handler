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

interface IJob
{

    /**
     * default constructor
     *
     * @param int $id
     * @param array $data
     */
    public function __construct(int $id, array $data);

    /**
     * process data
     *
     * @return void
     */
    public function process(): void;

}