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

class IncomingDice
{

    /** @var string $emoji emoji used in dice */
    public string $emoji;

    /** @var int $value dice value */
    public int $value;

    /**
     * default constructor
     *
     * @param array $incomingDice
     */
    public function __construct(protected readonly array $incomingDice)
    {
        $this->emoji = $this->incomingDice['emoji'];
        $this->value = $this->incomingDice['value'];
    }

}