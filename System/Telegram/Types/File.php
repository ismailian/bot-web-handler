<?php

namespace TeleBot\System\Telegram\Types;

use TeleBot\System\Telegram\Traits\Downloadable;

class File
{

    use Downloadable;

    /** @var string $fileId file id */
    public string $fileId;

    /** @var string $fileUniqueId unique file id */
    public string $fileUniqueId;

    /** @var int|null $fileSize */
    public ?int $fileSize = null;

    /** @var string|null $filePath file path */
    public ?string $filePath = null;

    /**
     * convert byte file size to human-readable format
     *
     * @return string|null human-readable file size
     */
    public function toHumanReadable(): ?string
    {
        if (!$this->fileSize) return null;

        $n = 0;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        while ($this->fileSize >= 1024 && $n++ < count($units)) {
            $this->fileSize /= 1024;
        }

        return join(' ', [number_format((float) $this->fileSize, 2), $units[$n]]);
    }
}