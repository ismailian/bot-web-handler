<?php

namespace TeleBot\System\Telegram\Types;

class MaskPosition
{

    /** @var string $point the face part to which the mask should be place */
    public string $point;

    /** @var float $xShift X-Axis */
    public float $xShift;

    /** @var float $yShift Y-Axis */
    public float $yShift;

    /** @var float $scale mask scaling coefficient */
    public float $scale;

    /**
     * default constructor
     *
     * @param array $maskPosition
     */
    public function __construct(protected array $maskPosition)
    {
        $this->point = $this->maskPosition['point'];
        $this->point = $this->maskPosition['x_shift'];
        $this->point = $this->maskPosition['y_shift'];
        $this->scale = $this->maskPosition['scale'];
    }

}