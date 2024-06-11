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

class IncomingVenue
{

    /** @var IncomingLocation $location venue location. Can't be a live location */
    public IncomingLocation $location;

    /** @var string venue name */
    public string $title;

    /** @var string $address venue address */
    public string $address;

    /** @var string|null $foursquareId Foursquare identifier of the venue */
    public ?string $foursquareId = null;

    /**
     * @var string|null $foursquareType
     * Foursquare type of the venue.
     * (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.)
     */
    public ?string $foursquareType = null;

    /** @var string|null $googlePlaceId Google Places identifier of the venue */
    public ?string $googlePlaceId = null;

    /** @var string|null $googlePlaceType Google Places type of the venue */
    public ?string $googlePlaceType = null;

    /**
     * default constructor
     *
     * @param array $incomingVenue
     */
    public function __construct(protected readonly array $incomingVenue)
    {
        $this->location = new IncomingLocation($this->incomingVenue['location']);
        $this->title = $this->incomingVenue['title'];
        $this->address = $this->incomingVenue['address'];
        $this->foursquareId = $this->incomingVenue['foursquare_id'] ?? null;
        $this->foursquareType = $this->incomingVenue['foursquare_type'] ?? null;
        $this->googlePlaceId = $this->incomingVenue['google_place_id'] ?? null;
        $this->googlePlaceType = $this->incomingVenue['google_place_type'] ?? null;
    }

}