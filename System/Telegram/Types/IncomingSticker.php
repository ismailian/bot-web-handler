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

use TeleBot\System\Telegram\Traits\MapProp;
use TeleBot\System\Telegram\Support\Hydrator;

class IncomingSticker extends File
{

    /** @var int $width video width */
    #[MapProp('width')]
    public int $width;

    /** @var int $height video height */
    #[MapProp('height')]
    public int $height;

    /** @var string $type sticker type */
    #[MapProp('type')]
    public string $type;

    /** @var bool $isAnimated is animated */
    #[MapProp('is_animated')]
    public bool $isAnimated;

    /** @var bool $isVideo is video */
    #[MapProp('is_video')]
    public bool $isVideo;

    /** @var string|null $emoji emoji value */
    #[MapProp('emoji')]
    public ?string $emoji = null;

    /** @var string|null $setName name of sticker set */
    #[MapProp('set_name')]
    public ?string $setName = null;

    /** @var File|null $premiumAnimation sticker premium animation */
    #[MapProp('premium_animation', File::class)]
    public ?File $premiumAnimation = null;

    /** @var MaskPosition|null $maskPosition mask position for the sticker */
    #[MapProp('mask_position', MaskPosition::class)]
    public ?MaskPosition $maskPosition = null;

    /** @var string|null $customEmojiId unique id for custom emoji sticker */
    #[MapProp('custom_emoji_id')]
    public ?string $customEmojiId = null;

    /** @var bool|null $needsRepainting true if sticker must be repainted toa text color in messages */
    #[MapProp('need_repainting')]
    public ?bool $needsRepainting = null;

    /** @var PhotoSize|null $thumbnail animation thumbnail */
    #[MapProp('thumbnail', PhotoSize::class)]
    public ?PhotoSize $thumbnail = null;

    /**
     * default constructor
     *
     * @param array $incomingAnimation
     */
    public function __construct(array $incomingAnimation)
    {
        Hydrator::hydrate($this, $incomingAnimation);
    }

}