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

class IncomingAudio extends File
{

    /** @var int $duration audio duration */
    public int $duration;

    /** @var string|null $performer audio performer */
    public ?string $performer = null;

    /** @var string|null $title audio title */
    public ?string $title = null;

    /** @var string|null $fileName audio file name */
    public ?string $fileName = null;

    /** @var string|null $mimeType mime type */
    public ?string $mimeType = null;

    /** @var PhotoSize|null $thumbnail audio thumbnail */
    public ?PhotoSize $thumbnail = null;

    /**
     * default constructor
     *
     * @param array $incomingAudio
     */
    public function __construct(protected readonly array $incomingAudio)
    {
        $this->fileId = $this->incomingAudio['file_id'];
        $this->fileUniqueId = $this->incomingAudio['file_unique_id'];
        $this->duration = $this->incomingAudio['duration'];

        if (array_key_exists('performer', $this->incomingAudio)) {
            $this->performer = $this->incomingAudio['performer'];
        }

        if (array_key_exists('title', $this->incomingAudio)) {
            $this->title = $this->incomingAudio['title'];
        }

        if (array_key_exists('file_name', $this->incomingAudio)) {
            $this->fileName = $this->incomingAudio['file_name'];
        }

        if (array_key_exists('mime_type', $this->incomingAudio)) {
            $this->mimeType = $this->incomingAudio['mime_type'];
        }

        if (array_key_exists('file_size', $this->incomingAudio)) {
            $this->fileSize = $this->incomingAudio['file_size'];
        }

        if (array_key_exists('thumbnail', $this->incomingAudio)) {
            $this->thumbnail = new PhotoSize($this->incomingAudio['thumbnail']);
        }
    }

}