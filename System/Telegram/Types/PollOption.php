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

use TeleBot\System\Telegram\Traits\MapProp;
use TeleBot\System\Telegram\Support\Hydrator;

class PollOption
{

    /** @var string $text option text */
    #[MapProp('text')]
    public string $text;

    /** @var MessageEntities|null $textEntities special entities in the text */
    public ?MessageEntities $textEntities = null;

    /** @var int $voterCount number of votes */
    #[MapProp('voter_count')]
    public int $voterCount;

    /**
     * Default constructor
     *
     * @param array $pollOption
     */
    public function __construct(array $pollOption)
    {
        Hydrator::hydrate($this, $pollOption);
        if (array_key_exists('text_entities', $pollOption)) {
            $this->textEntities = new MessageEntities($pollOption, 'text_entities');
        }
    }

}