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
use TeleBot\System\Telegram\Types\IncomingUrl;

#[Attribute(Attribute::TARGET_METHOD)]
class Url implements IEvent
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
    public function apply(array $event): bool|IncomingUrl
    {
        if (!$this->isMessage(array_keys($event))) return false;

        $key = $this->first(array_keys($event));
        if (!array_key_exists('text', $event[$key])) return false;

        foreach ($event[$key]['entities'] ?? [] as $entity) {
            if ($entity['type'] === 'url') {
                $url = new IncomingUrl($event[$key]['text'], $entity);
                if (!$this->validator || $this->validator->isValid($url)) {
                    return $url;
                }
            }
        }

        return false;
    }
}