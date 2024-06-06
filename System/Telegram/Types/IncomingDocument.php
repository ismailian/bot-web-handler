<?php

namespace TeleBot\System\Telegram\Types;

class IncomingDocument extends File
{

    /** @var string|null $fileName file name */
    public ?string $fileName = null;

    /** @var string|null $mimeType mime type */
    public ?string $mimeType = null;

    /** @var PhotoSize|null $thumbnail file thumbnail */
    public ?PhotoSize $thumbnail = null;

    /**
     * default constructor
     *
     * @param array $incomingDocument
     */
    public function __construct(protected array $incomingDocument)
    {
        $this->fileId = $this->incomingDocument['file_id'];
        $this->fileUniqueId = $this->incomingDocument['file_unique_id'];

        if (array_key_exists('file_name', $this->incomingDocument)) {
            $this->fileName = $this->incomingDocument['file_name'];
        }

        if (array_key_exists('mime_type', $this->incomingDocument)) {
            $this->mimeType = $this->incomingDocument['mime_type'];
        }

        if (array_key_exists('file_size', $this->incomingDocument)) {
            $this->fileSize = $this->incomingDocument['file_size'];
        }

        if (array_key_exists('thumbnail', $this->incomingDocument)) {
            $this->thumbnail = new PhotoSize($this->incomingDocument['thumbnail']);
        }
    }

}