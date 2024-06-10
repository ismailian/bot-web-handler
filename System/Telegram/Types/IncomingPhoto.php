<?php

namespace TeleBot\System\Telegram\Types;

class IncomingPhoto
{

    /** @var PhotoSize[] $photos */
    public array $photos;

    /**
     * default constructor
     *
     * @param array $incomingPhoto
     */
    public function __construct(protected readonly array $incomingPhoto)
    {
        $this->photos = array_map(
            fn($photoSize) => new PhotoSize($photoSize),
            $this->incomingPhoto
        );
    }

}