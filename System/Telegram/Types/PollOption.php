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

class PollOption
{

    /** @var string $text option text */
    public string $text;

    /** @var MessageEntity[]|null $textEntities special entities in the text */
    public ?array $textEntities = null;

    /** @var int $voterCount number of votes */
    public int $voterCount;

    public function __construct(protected readonly array $pollOption)
    {
        $this->text = $this->pollOption['text'];
        $this->voterCount = $this->pollOption['voter_count'];
        if (!empty($pollOption['text_entities'])) {
            $this->textEntities = array_map(
                fn($e) => new MessageEntity($this->text, $e),
                $this->pollOption['text_entities']
            );
        }
    }

}