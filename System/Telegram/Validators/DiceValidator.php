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

class DiceValidator implements IValidator
{

    /**
     * default constructor
     *
     * @param int|null $min
     * @param int|null $lessThan
     * @param int|null $greaterThan
     * @param int|null $equals
     */
    public function __construct(
        public ?int $min = null,
        public ?int $lessThan = null,
        public ?int $greaterThan = null,
        public ?int $equals = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function isValid(mixed $data): bool
    {
        if (
            !is_numeric($data['value'])
            || ($this->min && $this->min > $data['value'])
            || ($this->equals && $this->equals !== $data['value'])
            || ($this->lessThan && $this->lessThan <= $data['value'])
            || ($this->greaterThan && $this->greaterThan >= $data['value'])
        ) return false;
        return true;
    }
}