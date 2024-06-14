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

class BackgroundType
{

    /** @var string $type Type of the background */
    public string $type;

    /** @var BackgroundFill|null $fill The background fill */
    public ?BackgroundFill $fill = null;

    /** @var int|null $darkThemeDimming
     * Dimming of the background in dark themes, as a percentage; 0-100
     */
    public ?int $darkThemeDimming = null;

    /** @var IncomingDocument|null $document Document with the wallpaper */
    public ?IncomingDocument $document = null;

    /** @var bool|null $isBlurred
     * True, if the wallpaper is downscaled to fit in a 450x450 square and then box-blurred with radius 12
     */
    public ?bool $isBlurred = null;

    /** @var bool|null $isMoving True, if the background moves slightly when the device is tilted */
    public ?bool $isMoving = null;

    /** @var int|null $intensity Intensity of the pattern when it is shown above the filled background; 0-100 */
    public ?int $intensity = null;

    /** @var bool|null $isInverted
     * True, if the background fill must be applied only to the pattern itself.
     * All other pixels are black in this case. For dark themes only
     */
    public ?bool $isInverted = null;

    /** @var string|null $themeName Name of the chat theme, which is usually an emoji */
    public ?string $themeName = null;

    /**
     * default constructor
     *
     * @param array $backgroundType
     */
    public function __construct(protected readonly array $backgroundType)
    {
        $this->type = $this->backgroundType['type'];
        $this->isMoving = $this->backgroundType['is_moving'] ?? null;
        $this->intensity = $this->backgroundType['intensity'] ?? null;
        $this->themeName = $this->backgroundType['theme_name'] ?? null;
        $this->isBlurred = $this->backgroundType['is_blurred'] ?? null;
        $this->darkThemeDimming = $this->backgroundType['dark_theme_dimming'] ?? null;

        if (array_key_exists('fill', $this->backgroundType)) {
            $this->fill = new BackgroundFill($this->backgroundType['fill']);
        }

        if (array_key_exists('document', $this->backgroundType)) {
            $this->document = new IncomingDocument($this->backgroundType['document']);
        }
    }

}