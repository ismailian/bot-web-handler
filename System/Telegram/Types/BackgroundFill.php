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

class BackgroundFill
{

    /** @var string $type Type of the background fill */
    public string $type;

    /** @var string|null $color The color of the background fill in the RGB24 format */
    public ?string $color = null;

    /** @var string|null $topColor Top color of the gradient in the RGB24 format */
    public ?string $topColor = null;

    /** @var string|null $bottomColor Bottom color of the gradient in the RGB24 format */
    public ?string $bottomColor = null;

    /** @var string|null $rotationColor Clockwise rotation angle of the background fill in degrees; 0-359 */
    public ?string $rotationColor = null;

    /** @var array|null $colors
     * A list of the 3 or 4 base colors that are used to generate the freeform gradient in the RGB24 format
     */
    public ?array $colors = null;

    /**
     * default constructor
     *
     * @param array $backgroundFill
     */
    public function __construct(protected readonly array $backgroundFill)
    {
        $this->type = $this->backgroundFill['type'];
        $this->color = $this->backgroundFill['color'] ?? null;
        $this->topColor = $this->backgroundFill['top_color'] ?? null;
        $this->bottomColor = $this->backgroundFill['bottom_color'] ?? null;
        $this->rotationColor = $this->backgroundFill['rotation_color'] ?? null;
        $this->colors = $this->backgroundFill['colors'] ?? null;
    }

}