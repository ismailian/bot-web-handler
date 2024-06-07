<?php

namespace TeleBot\System\Telegram\Types;

class IncomingGame
{

    /** @var string $title game title */
    public string $title;

    /** @var string $description game description */
    public string $description;

    /** @var PhotoSize $photo game cover */
    public PhotoSize $photo;

    /** @var string|null $text Brief description of the game or high scores included in the game message */
    public ?string $text = null;

    /** @var Entity[]|null $textEntities Special entities that appear in text */
    public ?array $textEntities = null;

    /** @var IncomingAnimation|null $animation Animation that will be displayed in the game message in chats */
    public ?IncomingAnimation $animation = null;

    /**
     * default constructor
     *
     * @param array $incomingGame
     */
    public function __construct(protected array $incomingGame)
    {
        $this->title = $this->incomingGame['title'];
        $this->description = $this->incomingGame['description'];
        $this->text = $this->incomingGame['text'] ?? null;
        if (array_key_exists('text', $this->incomingGame)) {
            $this->textEntities = array_map(
                fn($e) => new Entity($this->text, $e),
                $this->incomingGame['textEntities']
            );
        }

        if (array_key_exists('photo', $this->incomingGame)) {
            $this->photo = new PhotoSize($this->incomingGame['photo']);
        }

        if (array_key_exists('animation', $this->incomingGame)) {
            $this->animation = new IncomingAnimation($this->incomingGame['animation']);
        }
    }
}