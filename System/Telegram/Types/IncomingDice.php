<?php

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
    public function __construct(protected array $incomingDice)
    {
        $this->emoji = $this->incomingDice['emoji'];
        $this->value = $this->incomingDice['value'];
    }

}