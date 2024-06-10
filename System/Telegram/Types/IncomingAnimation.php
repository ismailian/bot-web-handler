<?php

namespace TeleBot\System\Telegram\Types;

class IncomingAnimation extends File
{

    /** @var int $width video width */
    public int $width;

    /** @var int $height video height */
    public int $height;

    /** @var int $duration video duration */
    public int $duration;

    /** @var PhotoSize|null $thumbnail animation thumbnail */
    public ?PhotoSize $thumbnail = null;

    /** @var string|null $fileName animation file name */
    public ?string $fileName;

    /** @var string|null $mimeType file mime type */
    public ?string $mimeType = null;

    /**
     * default constructor
     *
     * @param array $incomingAnimation
     */
    public function __construct(protected readonly array $incomingAnimation)
    {
        $this->fileId = $this->incomingAnimation['file_id'];
        $this->fileUniqueId = $this->incomingAnimation['file_unique_id'];
        $this->duration = $this->incomingAnimation['duration'];
        $this->width = $this->incomingAnimation['width'];
        $this->height = $this->incomingAnimation['height'];

        if (array_key_exists('file_name', $this->incomingAnimation)) {
            $this->fileName = $this->incomingAnimation['file_name'];
        }

        if (array_key_exists('file_size', $this->incomingAnimation)) {
            $this->fileSize = $this->incomingAnimation['file_size'];
        }

        if (array_key_exists('mime_type', $this->incomingAnimation)) {
            $this->mimeType = $this->incomingAnimation['mime_type'];
        }

        if (array_key_exists('thumbnail', $this->incomingAnimation)) {
            $this->thumbnail = new PhotoSize($this->incomingAnimation['thumbnail']);
        }
    }

}