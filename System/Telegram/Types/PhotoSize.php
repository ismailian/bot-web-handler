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

class PhotoSize extends File
{

    /** @var int $width photo width */
    #[MapProp('width')]
    public int $width;

    /** @var int $height photo height */
    #[MapProp('height')]
    public int $height;

    /**
     * default constructor
     *
     * @param array $photoSize
     */
    public function __construct(array $photoSize)
    {
        Hydrator::hydrate($this, $photoSize);
    }

}