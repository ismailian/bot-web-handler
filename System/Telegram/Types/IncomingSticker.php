<?php

namespace TeleBot\System\Telegram\Types;

class IncomingSticker extends File
{

    /** @var int $width video width */
    public int $width;

    /** @var int $height video height */
    public int $height;

    /** @var string $type sticker type */
    public string $type;

    /** @var bool $isAnimated is animated */
    public bool $isAnimated;

    /** @var bool $isVideo is video */
    public bool $isVideo;

    /** @var string|null $emoji emoji value */
    public ?string $emoji = null;

    /** @var string|null $setName name of sticker set */
    public ?string $setName = null;

    /** @var File|null $premiumAnimation sticker premium animation */
    public ?File $premiumAnimation = null;

    /** @var MaskPosition|null $maskPosition mask position for the sticker */
    public ?MaskPosition $maskPosition = null;

    /** @var string|null $customEmojiId unique id for custom emoji sticker */
    public ?string $customEmojiId = null;

    /** @var bool|null $needsRepainting true if sticker must be repainted toa text color in messages */
    public ?bool $needsRepainting = null;

    /** @var PhotoSize|null $thumbnail animation thumbnail */
    public ?PhotoSize $thumbnail = null;

    /**
     * default constructor
     *
     * @param array $incomingAnimation
     */
    public function __construct(protected readonly array $incomingAnimation)
    {
        $this->fileId = $this->incomingAnimation['file_id'];
        $this->fileUniqueId = $this->incomingAnimation['file_unique_id'];
        $this->width = $this->incomingAnimation['width'];
        $this->height = $this->incomingAnimation['height'];
        $this->isAnimated = $this->incomingAnimation['is_animated'];
        $this->isVideo = $this->incomingAnimation['is_video'];
        $this->type = $this->incomingAnimation['type'];

        if (array_key_exists('emoji', $this->incomingAnimation)) {
            $this->emoji = $this->incomingAnimation['emoji'];
        }

        if (array_key_exists('set_name', $this->incomingAnimation)) {
            $this->setName = $this->incomingAnimation['set_name'];
        }

        if (array_key_exists('premium_animation', $this->incomingAnimation)) {
            $this->premiumAnimation = $this->incomingAnimation['premium_animation'];
        }

        if (array_key_exists('custom_emoji_id', $this->incomingAnimation)) {
            $this->customEmojiId = $this->incomingAnimation['custom_emoji_id'];
        }

        if (array_key_exists('needs_repainting', $this->incomingAnimation)) {
            $this->needsRepainting = $this->incomingAnimation['needs_repainting'];
        }

        if (array_key_exists('mask_position', $this->incomingAnimation)) {
            $this->maskPosition = new MaskPosition($this->incomingAnimation['mask_position']);
        }

        if (array_key_exists('file_size', $this->incomingAnimation)) {
            $this->fileSize = $this->incomingAnimation['file_size'];
        }

        if (array_key_exists('thumbnail', $this->incomingAnimation)) {
            $this->thumbnail = new PhotoSize($this->incomingAnimation['thumbnail']);
        }
    }

}