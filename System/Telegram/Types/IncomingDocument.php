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

class IncomingDocument extends File
{

    /** @var string|null $fileName file name */
    #[MapProp('file_name')]
    public ?string $fileName = null;

    /** @var string|null $mimeType mime type */
    #[MapProp('mime_type')]
    public ?string $mimeType = null;

    /** @var PhotoSize|null $thumbnail file thumbnail */
    #[MapProp('thumbnail', PhotoSize::class)]
    public ?PhotoSize $thumbnail = null;

    /**
     * default constructor
     *
     * @param array $incomingDocument
     */
    public function __construct(array $incomingDocument)
    {
        Hydrator::hydrate($this, $incomingDocument);
    }

}