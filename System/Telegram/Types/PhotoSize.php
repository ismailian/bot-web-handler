<?php

namespace TeleBot\System\Telegram\Types;

class PhotoSize extends File
{

    /** @var int $width photo width */
    public int $width;

    /** @var int $height photo height */
    public int $height;

    /**
     * default constructor
     *
     * @param array $photoSize
     */
    public function __construct(protected array $photoSize)
    {
        $this->fileId = $this->photoSize['file_id'];
        $this->fileUniqueId = $this->photoSize['file_unique_id'];
        $this->width = $this->photoSize['width'];
        $this->height = $this->photoSize['height'];

        if (array_key_exists('file_size', $this->photoSize)) {
            $this->fileSize = $this->photoSize['file_size'];
        }
    }

}