<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Types;

class IncomingPhoneNumber
{

    /** @var string $value phone number */
    public readonly string $value;

    /**
     * default constructor
     *
     * @param string $text
     * @param array $entity
     */
    public function __construct(protected string $text, protected array $entity)
    {
        $this->value = mb_substr($this->text, $entity['offset'], $entity['length']);
    }

    /**
     * get phone number value
     */
    public function __toString(): string
    {
        return $this->value;
    }

}