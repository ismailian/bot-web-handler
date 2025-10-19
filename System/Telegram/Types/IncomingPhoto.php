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

use TeleBot\System\Telegram\Support\MediaIterator;

class IncomingPhoto
{

    use MediaIterator;

    /** @var PhotoSize[] $variations */
    public array $variations;

    /** @var PhotoSize $small the smallest variation of the photo */
    public PhotoSize $small;

    /** @var PhotoSize $medium medium variation of the photo */
    public PhotoSize $medium;

    /** @var PhotoSize $large the largest variation of the photo */
    public PhotoSize $large;

    /**
     * default constructor
     *
     * @param array $incomingPhoto
     */
    public function __construct(protected readonly array $incomingPhoto)
    {
        $this->variations = array_map(
            fn($photoSize) => new PhotoSize($photoSize),
            $this->incomingPhoto
        );

        usort($this->variations, function (PhotoSize $a, PhotoSize $b) {
            return $a->width <=> $b->width;
        });

        $this->small = $this->variations[0];
        $this->medium = $this->variations[1];
        $this->large = $this->variations[2];
        $this->variable = 'variations';
    }

}