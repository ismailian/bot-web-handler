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

class IncomingGame
{

    /** @var string $title game title */
    #[MapProp('title')]
    public string $title;

    /** @var string $description game description */
    #[MapProp('description')]
    public string $description;

    /** @var string|null $text Brief description of the game or high scores included in the game message */
    #[MapProp('text')]
    public ?string $text = null;

    /** @var PhotoSize $photo game cover */
    #[MapProp('photo', PhotoSize::class)]
    public PhotoSize $photo;

    /** @var MessageEntities|null $textEntities Special entities that appear in text */
    public ?MessageEntities $textEntities = null;

    /** @var IncomingAnimation|null $animation Animation that will be displayed in the game message in chats */
    #[MapProp('animation', IncomingAnimation::class)]
    public ?IncomingAnimation $animation = null;

    /**
     * default constructor
     *
     * @param array $incomingGame
     */
    public function __construct(array $incomingGame)
    {
        Hydrator::hydrate($this, $incomingGame);
        if (array_key_exists('text_entities', $incomingGame)) {
            $this->textEntities = new MessageEntities($incomingGame, 'text_entities');
        }
    }
}