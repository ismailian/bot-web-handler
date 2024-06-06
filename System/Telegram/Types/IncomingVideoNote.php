<?php

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
    public function __construct(protected array $incomingVideoNote)
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