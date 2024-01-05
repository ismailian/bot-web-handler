<?php

namespace TeleBot\System\Types;

class Thumbnail extends File
{

    /**
     * get file id
     *
     * @return string
     */
    public function getFileId(): string
    {
        return $this->file['file_id'];
    }

    /**
     * get thumbnail width
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->file['width'];
    }

    /**
     * get thumbnail height
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->file['height'];
    }

    /**
     * get thumbnail file size
     *
     * @param bool $readable
     * @return int|string
     */
    public function getSize(bool $readable = false): int|string
    {
        if (!$readable) return $this->file['file_size'];
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $fileSize = $this->file['file_size'];
        $n = 0;

        while ($fileSize >= 1024 && $n++ < count($units))
            $fileSize /= 1024;
        return join(' ', [number_format($fileSize, 2), $units[$n]]);
    }

    /**
     * download thumbnail
     *
     * @return string|null
     */
    public function save(): ?string
    {
        $this->getLink($this->getFileId());
        return $this->saveAs();
    }

}