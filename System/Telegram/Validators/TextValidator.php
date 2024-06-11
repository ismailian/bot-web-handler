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

class TextValidator implements IValidator
{

    /**
     * default constructor
     *
     * @param int|null $minLength
     * @param string|null $equals
     * @param string|null $regex
     */
    public function __construct(
        public ?int    $minLength = null,
        public ?string $equals = null,
        public ?string $regex = null
    ) {}

    /**
     * @inheritDoc
     */
    public function isValid(mixed $data): bool
    {
        if (
            ($this->minLength && $this->minLength > strlen($data))
            || ($this->equals && $this->equals !== $data)
            || ($this->regex && !((bool)preg_match("/{$this->regex}/i", $data)))
        ) return false;
        return true;
    }
}