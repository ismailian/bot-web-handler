<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function __construct(protected readonly array $incomingVoice)
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