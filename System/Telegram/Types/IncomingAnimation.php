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

class IncomingAnimation extends File
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

    /** @var PhotoSize|null $thumbnail animation thumbnail */
    #[MapProp('thumbnail', PhotoSize::class)]
    public ?PhotoSize $thumbnail = null;

    /** @var string|null $fileName animation file name */
    #[MapProp('file_name')]
    public ?string $fileName;

    /** @var string|null $mimeType file mime type */
    #[MapProp('mime_type')]
    public ?string $mimeType = null;

    /**
     * default constructor
     *
     * @param array $incomingAnimation
     */
    public function __construct(array $incomingAnimation)
    {
        Hydrator::hydrate($this, $incomingAnimation);
    }

}