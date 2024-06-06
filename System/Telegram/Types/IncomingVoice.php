<?php

namespace TeleBot\System\Telegram\Types;

class IncomingVoice extends File
{

    /** @var int $duration audio duration */
    public int $duration;

    /** @var string|null $mimeType mime type */
    public ?string $mimeType = null;

    /**
     * default constructor
     *
     * @param array $incomingVoice
     */
    public function __construct(protected array $incomingVoice)
    {
        $this->fileId = $this->incomingVoice['file_id'];
        $this->fileUniqueId = $this->incomingVoice['file_unique_id'];
        $this->duration = $this->incomingVoice['duration'];

        if (array_key_exists('mime_type', $this->incomingVoice)) {
            $this->mimeType = $this->incomingVoice['mime_type'];
        }

        if (array_key_exists('file_size', $this->incomingVoice)) {
            $this->fileSize = $this->incomingVoice['file_size'];
        }
    }
}