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

class PaidMediaVideo
{

    /** @var string|mixed $type Type of the paid media, always “photo” */
    public string $type = "photo";

    /** @var IncomingVideo $video The video */
    public IncomingVideo $video;

    /**
     * default constructor
     *
     * @param array $paidMediaVideo
     */
    public function __construct(protected readonly array $paidMediaVideo)
    {
        $this->type = $this->paidMediaVideo['type'];
        $this->video = new IncomingVideo($this->paidMediaVideo['video']);
    }

}