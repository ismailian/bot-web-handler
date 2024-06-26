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

class NumberValidator implements IValidator
{

    /**
     * default constructor
     *
     * @param int|null $min
     * @param int|null $max
     * @param int|null $equals
     * @param int|null $greaterThan
     * @param int|null $lessThan
     */
    public function __construct(
        public ?int $min = null,
        public ?int $max = null,
        public ?int $equals = null,
        public ?int $greaterThan = null,
        public ?int $lessThan = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function isValid(mixed $data): bool
    {
        if (!is_numeric($data)) return false;

        if ($this->equals && $data !== $this->equals) return false;
        if ($this->lessThan && $data >= $this->lessThan) return false;
        if ($this->greaterThan && $data <= $this->greaterThan) return false;
        if ($this->min && $data < $this->min) return false;
        if ($this->max && $data > $this->max) return false;

        return true;
    }
}