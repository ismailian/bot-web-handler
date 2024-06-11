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

interface IValidator
{

    /**
     * check if data matches the validation criteria
     *
     * @param mixed $data
     * @return bool
     */
    public function isValid(mixed $data): bool;

}