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

class PaidMediaPreview
{

    /** @var string $type Type of the paid media, always “preview” */
    public string $type = "preview";

    /**
     * @var int|null $width Optional.
     * Media width as defined by the sender
     */
    public ?int $width = null;

    /**
     * @var int|null $height Optional.
     * Media height as defined by the sender
     */
    public ?int $height = null;

    /**
     * @var int|null $duration Optional.
     * Duration of the media in seconds as defined by the sender
     */
    public ?int $duration = null;

    /**
     * default constructor
     *
     * @param array $paidMediaPreview
     */
    public function __construct(protected readonly array $paidMediaPreview)
    {
        $this->type = $this->paidMediaPreview['type'];
        $this->width = $this->paidMediaPreview['width'] ?? null;
        $this->height = $this->paidMediaPreview['height'] ?? null;
        $this->duration = $this->paidMediaPreview['duration'] ?? null;
    }

}