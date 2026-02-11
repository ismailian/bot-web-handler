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

class TextQuote
{

    /** @var string $text
     * Text of the quoted part of a message that is replied to by the given message
     */
    public string $text;

    /** @var MessageEntities|null $entities text entities */
    public ?MessageEntities $entities = null;

    /** @var int $position
     * Approximate quote position in the original message in UTF-16 code units as specified by the sender
     */
    public int $position;

    /** @var bool|null $isManual
     * True, if the quote was chosen manually by the message sender. Otherwise, the quote was added automatically by the server.
     */
    public ?bool $isManual = null;

    /**
     * default constructor
     *
     * @param array $textQuote
     */
    public function __construct(array $textQuote)
    {
        $this->text = $textQuote['text'];
        $this->position = $textQuote['position'];
        $this->isManual = $textQuote['is_manual'] ?? null;
        if (array_key_exists('entities', $textQuote)) {
            $this->entities = new MessageEntities($textQuote);
        }
    }

}