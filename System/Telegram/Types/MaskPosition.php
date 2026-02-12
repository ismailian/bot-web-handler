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

class MaskPosition
{

    /** @var string $point the face part to which the mask should be place */
    #[MapProp('point')]
    public string $point;

    /** @var float $xShift X-Axis */
    #[MapProp('x_shift')]
    public float $xShift;

    /** @var float $yShift Y-Axis */
    #[MapProp('y_shift')]
    public float $yShift;

    /** @var float $scale mask scaling coefficient */
    #[MapProp('scale')]
    public float $scale;

    /**
     * default constructor
     *
     * @param array $maskPosition
     */
    public function __construct(array $maskPosition)
    {
        Hydrator::hydrate($this, $maskPosition);
    }

}