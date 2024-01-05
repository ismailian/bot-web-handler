<?php

namespace TeleBot\System\Types;

class IncomingDocument extends File
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
     * get document file name
     *
     * @return string
     */
    public function getFilename(): string
    {
        return $this->file['file_name'];
    }

    /**
     * get document mime type
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->file['mime_type'];
    }

    /**
     * get document file size
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
     * get document thumbnail
     *
     * @return Thumbnail
     */
    public function getThumbnail(): Thumbnail
    {
        return new Thumbnail($this->file['thumbnail']);
    }

    /**
     * download document
     *
     * @return string|null returns stored file name
     */
    public function save(): ?string
    {
        $this->getLink($this->getFileId());
        return $this->saveAs();
    }

}