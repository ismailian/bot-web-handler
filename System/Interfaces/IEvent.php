<?php

namespace TeleBot\System\Interfaces;

interface IEvent
{

    /**
     * verify event type
     *
     * @param array $event
     * @return bool
     */
    public function apply(array $event): bool;

}