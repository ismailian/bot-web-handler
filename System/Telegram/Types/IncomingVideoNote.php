<?php

namespace TeleBot\System\Telegram\Types;

class IncomingVideoNote extends File
{

    /** @var string $fileId file id */
    public string $fileId;

    /** @var string $fileUniqueId file unique id */
    public string $fileUniqueId;

    /** @var int $length video width and height */
    public int $length;

    /** @var int $duration video duration */
    public int $duration;

    /** @var Thumbnail|null $thumbnail video thumbnail */
    public ?Thumbnail $thumbnail;

    /** @var string|null $fileSize file size */
    public ?string $fileSize = null;

    /**
     * download audio
     *
     * @return string|null returns stored file name
     */
    public function save(): ?string
    {
        $this->getLink($this->fileId);
        return $this->saveAs();
    }

}