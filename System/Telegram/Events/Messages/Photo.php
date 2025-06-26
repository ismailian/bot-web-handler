<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Events\Messages;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Interfaces\IValidator;
use TeleBot\System\Telegram\Traits\Messageable;
use TeleBot\System\Telegram\Types\IncomingPhoto;

#[Attribute(Attribute::TARGET_METHOD)]
class Photo implements IEvent
{

    use Messageable;

    /**
     * default constructor
     *
     * @param IValidator|null $validator
     */
    public function __construct(public ?IValidator $validator = null) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingPhoto|bool
    {
        if (!$this->isMessage(array_keys($event))) return false;

        $key = $this->first(array_keys($event));
        if (!array_key_exists('photo', $event[$key])) return false;

        if ($this->validator && !$this->validator->isValid($event[$key]['photo'])) return false;
        return new IncomingPhoto($event[$key]['photo']);
    }
}