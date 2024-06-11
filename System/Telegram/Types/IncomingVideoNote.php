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

class IncomingVideoNote extends File
{

    /** @var int $length video width and height */
    public int $length;

    /** @var int $duration video duration */
    public int $duration;

    /** @var PhotoSize|null $thumbnail video thumbnail */
    public ?PhotoSize $thumbnail = null;

    /**
     * default constructor
     *
     * @param array $incomingVideoNote
     */
    public function __construct(protected readonly array $incomingVideoNote)
    {
        $this->fileId = $this->incomingVideoNote['file_id'];
        $this->fileUniqueId = $this->incomingVideoNote['file_unique_id'];
        $this->length = $this->incomingVideoNote['length'];
        $this->duration = $this->incomingVideoNote['duration'];

        if (array_key_exists('thumbnail', $this->incomingVideoNote)) {
            $this->thumbnail = new PhotoSize($this->incomingVideoNote['thumbnail']);
        }

        if (array_key_exists('file_size', $this->incomingVideoNote)) {
            $this->fileSize = $this->incomingVideoNote['file_size'];
        }
    }

}