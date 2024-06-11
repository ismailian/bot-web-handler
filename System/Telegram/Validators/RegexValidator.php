<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Validators;

use TeleBot\System\Interfaces\IValidator;

class RegexValidator implements IValidator
{

    /**
     * default constructor
     *
     * @param string $regex
     */
    public function __construct(public string $regex) {}

    /**
     * @inheritDoc
     */
    public function isValid(mixed $data): bool
    {
        return (bool) preg_match("/{$this->regex}/i", $data);
    }
}