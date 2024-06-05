<?php

namespace TeleBot\System\Telegram\Types;

class IncomingAnimation
{

    /** @var string $fileId file id */
    public string $fileId;

    /** @var string $fileUniqueId file unique id */
    public string $fileUniqueId;

    /** @var int $width video width */
    public int $width;

    /** @var int $height video height */
    public int $height;

    /** @var int $duration video duration */
    public int $duration;

    /** @var Thumbnail|null $thumbnail animation thumbnail */
    public ?Thumbnail $thumbnail = null;

    /** @var string|null $fileName animation file name */
    public ?string $fileName;

    /** @var int|null $fileSize animation file size */
    public ?int $fileSize;

    /** @var string|null $mimeType file mime type */
    public ?string $mimeType = null;

}