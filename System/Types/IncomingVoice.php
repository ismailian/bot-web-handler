<?php

namespace TeleBot\System\Types;

class IncomingVoice extends File
{

    /**
     * get file id by index
     *
     * @return string
     */
    public function getFileId(): string
    {
        return $this->file['file_id'];
    }

    /**
     * get voice duration in seconds
     *
     * @return int
     */
    public function getDuration(): int
    {
        return $this->file['duration'];
    }

    /**
     * get voice mime type
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->file['mime_type'];
    }

    /**
     * get voice file size
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
     * download voice
     *
     * @return string|null returns stored file name
     */
    public function save(): ?string
    {
        $this->getLink($this->getFileId());
        return $this->saveAs();
    }

}