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

class IncomingAudio extends File
{

    /** @var int $duration audio duration */
    #[MapProp('duration')]
    public int $duration;

    /** @var string|null $performer audio performer */
    #[MapProp('performer')]
    public ?string $performer = null;

    /** @var string|null $title audio title */
    #[MapProp('title')]
    public ?string $title = null;

    /** @var string|null $fileName audio file name */
    #[MapProp('file_name')]
    public ?string $fileName = null;

    /** @var string|null $mimeType mime type */
    #[MapProp('mime_type')]
    public ?string $mimeType = null;

    /** @var PhotoSize|null $thumbnail audio thumbnail */
    #[MapProp('thumbnail', PhotoSize::class)]
    public ?PhotoSize $thumbnail = null;

    /**
     * default constructor
     *
     * @param array $incomingAudio
     */
    public function __construct(array $incomingAudio)
    {
        Hydrator::hydrate($this, $incomingAudio);
    }

}