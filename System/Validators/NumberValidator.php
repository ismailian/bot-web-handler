<?php

namespace TeleBot\System\Validators;

use TeleBot\System\Interfaces\IValidator;

class NumberValidator implements IValidator
{

    /**
     * default constructor
     *
     * @param int|null $min
     * @param int|null $max
     * @param array $rules
     */
    public function __construct(public ?int $min = null, public ?int $max = null, public array $rules = []) {}

    /**
     * @inheritDoc
     */
    public function isValid(mixed $data): bool
    {
        if (!is_numeric($data)) return false;
        if ($this->min && $data < $this->min) return false;
        if ($this->max && $data > $this->max) return false;

        return true;
    }
}