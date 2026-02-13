<?php

namespace TeleBot\System\Telegram\Support;

use TeleBot\System\Telegram\Enums\ParseMode;
use TeleBot\System\Telegram\Types\IncomingPhoto;
use TeleBot\System\Telegram\Types\IncomingAudio;
use TeleBot\System\Telegram\Types\IncomingVideo;
use TeleBot\System\Telegram\Types\IncomingMessage;

class CanReplyWith
{

    /**
     * Default constructor
     *
     * @param int $id
     * @param int|null $chat_id
     */
    public function __construct(
        protected int  $id,
        protected ?int $chat_id = null
    ) {}

    /**
     * Set parse mode
     *
     * @param ParseMode $mode parse mode
     * @return $this
     */
    public function withMode(ParseMode $mode): self
    {
        bot()->setParseMode($mode);

        return $this;
    }

    /**
     * Reply with a text message
     *
     * @param string $message
     * @return bool|IncomingMessage
     *
     * @see Send::sendMessage()
     */
    public function withText(string $message): bool|IncomingMessage
    {
        return bot()->replyTo($this->id, $this->chat_id)->sendMessage($message);
    }

    /**
     * Reply with a photo
     *
     * @param string $photo photo path or url
     * @param string|null $caption photo caption
     *
     * @see Send::sendPhoto()
     */
    public function withPhoto(string $photo, ?string $caption = null): bool|IncomingPhoto
    {
        return bot()->replyTo($this->id, $this->chat_id)->sendPhoto($photo, $caption);
    }

    /**
     * Reply with a video
     *
     * @param string $video
     * @param string|null $caption
     * @return bool|IncomingVideo
     *
     * @see Send::sendVideo()
     */
    public function withVideo(string $video, ?string $caption = null): bool|IncomingVideo
    {
        return bot()->replyTo($this->id, $this->chat_id)->sendVideo($video, $caption);
    }

    /**
     * Reply with audio
     *
     * @param string $audio
     * @param string|null $caption
     * @return bool|IncomingAudio
     *
     * @see Send::sendAudio()
     */
    public function withAudio(string $audio, ?string $caption = null): bool|IncomingAudio
    {
        return bot()->replyTo($this->id, $this->chat_id)->sendAudio($audio, $caption);
    }

}