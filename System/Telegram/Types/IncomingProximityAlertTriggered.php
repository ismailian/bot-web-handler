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

class IncomingProximityAlertTriggered
{

    /** @var User $traveler User that triggered the alert */
    public User $traveler;

    /** @var User $watcher User that set the alert */
    public User $watcher;

    /** @var int $distance The distance between the users */
    public int $distance;

    /**
     * default constructor
     *
     * @param array $incomingProximityAlertTriggered
     */
    public function __construct(protected readonly array $incomingProximityAlertTriggered)
    {
        $this->traveler = new User($this->incomingProximityAlertTriggered['traveler']);
        $this->watcher = new User($this->incomingProximityAlertTriggered['watcher']);
        $this->distance = $this->incomingProximityAlertTriggered['distance'];
    }

}