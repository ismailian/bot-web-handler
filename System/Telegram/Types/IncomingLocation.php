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

class IncomingLocation
{

    /** @var float $latitude latitude */
    public float $latitude;

    /** @var float $longitude longitude */
    public float $longitude;

    /** @var float|null $horizontalAccuracy horizontal accuracy */
    public ?float $horizontalAccuracy = null;

    /**
     * @var int|null $livePeriod
     * Time relative to the message sending date, during which the location can be updated; in seconds.
     */
    public ?int $livePeriod = null;

    /** @var int|null $heading The direction in which user is moving */
    public ?int $heading = null;

    /**
     * @var int|null $proximityAlertRadius
     * The maximum distance for proximity alerts about approaching another chat member, in meters
     */
    public ?int $proximityAlertRadius = null;

    /**
     * default constructor
     *
     * @param array $incomingLocation
     */
    public function __construct(protected readonly array $incomingLocation)
    {
        $this->latitude = $this->incomingLocation['latitude'];
        $this->longitude = $this->incomingLocation['longitude'];
        $this->horizontalAccuracy = $this->incomingLocation['horizontal_accuracy'] ?? null;
        $this->livePeriod = $this->incomingLocation['live_period'] ?? null;
        $this->heading = $this->incomingLocation['heading'] ?? null;
        $this->proximityAlertRadius = $this->incomingLocation['proximity_alert_radius'] ?? null;
    }
}