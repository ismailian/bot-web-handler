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

class IncomingVoice extends File
{

    /** @var int $duration audio duration */
    #[MapProp('duration')]
    public int $duration;

    /** @var string|null $mimeType mime type */
    #[MapProp('mime_type')]
    public ?string $mimeType = null;

    /**
     * default constructor
     *
     * @param array $incomingVoice
     */
    public function __construct(protected readonly array $incomingVoice)
    {
        Hydrator::hydrate($this, $incomingVoice);
    }
}