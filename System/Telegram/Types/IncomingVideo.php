<?php

namespace TeleBot\System\Telegram\Types;

class IncomingVideo extends File
{

    /** @var int $width video width */
    public int $width;

    /** @var int $height video height */
    public int $height;

    /** @var int $duration video duration */
    public int $duration;

    /** @var PhotoSize|null $thumbnail */
    public ?PhotoSize $thumbnail = null;

    /** @var string|null $fileName */
    public ?string $fileName = null;

    /** @var string|null $mimeType video mime type */
    public ?string $mimeType = null;

    /**
     * default constructor
     *
     * @param array $incomingVideo
     */
    public function __construct(protected array $incomingVideo)
    {
        $this->fileId = $this->incomingVideo['file_id'];
        $this->fileUniqueId = $this->incomingVideo['file_unique_id'];
        $this->width = $this->incomingVideo['width'];
        $this->height = $this->incomingVideo['height'];
        $this->duration = $this->incomingVideo['duration'];

        if (array_key_exists('thumbnail', $this->incomingVideo)) {
            $this->thumbnail = new PhotoSize($this->incomingVideo['thumbnail']);
        }

        if (array_key_exists('file_name', $this->incomingVideo)) {
            $this->fileName = $this->incomingVideo['file_name'];
        }

        if (array_key_exists('mime_type', $this->incomingVideo)) {
            $this->mimeType = $this->incomingVideo['mime_type'];
        }

        if (array_key_exists('file_size', $this->incomingVideo)) {
            $this->fileSize = $this->incomingVideo['file_size'];
        }
    }

}