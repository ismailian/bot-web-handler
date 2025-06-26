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
use TeleBot\System\Telegram\Types\IncomingMessage;

#[Attribute(Attribute::TARGET_METHOD)]
class Text implements IEvent
{

    use Messageable;

    /**
     * default constructor
     *
     * @param bool $cleanText capture plain-text only. This will only capture text messages without mentions, urls or commands
     * @param IValidator|null $validator
     */
    public function __construct(public bool $cleanText = false, public ?IValidator $validator = null) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingMessage|bool
    {
        if (!$this->isMessage(array_keys($event))) return false;

        $key = $this->first(array_keys($event));
        if (!array_key_exists('text', $event[$key])) return false;

        $isCleanText = !$this->cleanText || !count(array_filter(
                $event[$key]['entities'] ?? [],
                fn($entity) => in_array($entity['type'], ['bot_command', 'url', 'mention'])
            ));

        if ($isCleanText && (!$this->validator || $this->validator->isValid($event[$key]['text']))) {
            return new IncomingMessage($event[$key]);
        }

        return false;
    }
}