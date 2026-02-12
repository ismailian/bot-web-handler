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

class IncomingVideoNote extends File
{

    /** @var int $length video width and height */
    #[MapProp('length')]
    public int $length;

    /** @var int $duration video duration */
    #[MapProp('duration')]
    public int $duration;

    /** @var PhotoSize|null $thumbnail video thumbnail */
    #[MapProp('thumbnail', PhotoSize::class)]
    public ?PhotoSize $thumbnail = null;

    /**
     * default constructor
     *
     * @param array $incomingVideoNote
     */
    public function __construct(protected readonly array $incomingVideoNote)
    {
        Hydrator::hydrate($this, $incomingVideoNote);
    }

}