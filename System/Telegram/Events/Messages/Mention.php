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
use TeleBot\System\Telegram\Traits\Messageable;

#[Attribute(Attribute::TARGET_METHOD)]
class Mention implements IEvent
{

    use Messageable;

    /**
     * default constructor
     *
     * @param string|null $username username to check for mentions
     */
    public function __construct(public ?string $username = null)
    {
        if ($this->username == 'me') {
            $this->username = getenv('TG_BOT_USERNAME', true);
        }
    }

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        if (!$this->isMessage(array_keys($event))) return false;
        $key = $this->first(array_keys($event));

        if (!array_key_exists('text', $event[$key])) return false;
        if (!array_key_exists('entities', $event[$key])) return false;
        if (empty($event[$key]['entities'])) return false;

        foreach ($event[$key]['entities'] as $entity) {
            if (isset($entity['type']) && $entity['type'] == 'mention') {
                return !$this->username || substr(
                    $event[$key]['text'], $entity['offset'], $entity['length']
                ) == $this->username;
            }
        }

        return false;
    }
}