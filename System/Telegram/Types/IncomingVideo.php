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

class IncomingVideo extends File
{

    /** @var int $width video width */
    #[MapProp('width')]
    public int $width;

    /** @var int $height video height */
    #[MapProp('height')]
    public int $height;

    /** @var int $duration video duration */
    #[MapProp('duration')]
    public int $duration;

    /** @var PhotoSize|null $thumbnail */
    #[MapProp('thumbnail', PhotoSize::class)]
    public ?PhotoSize $thumbnail = null;

    /** @var string|null $fileName */
    #[MapProp('file_name')]
    public ?string $fileName = null;

    /** @var string|null $mimeType video mime type */
    #[MapProp('mime_type')]
    public ?string $mimeType = null;

    /**
     * default constructor
     *
     * @param array $incomingVideo
     */
    public function __construct(array $incomingVideo)
    {
        Hydrator::hydrate($this, $incomingVideo);
    }

}