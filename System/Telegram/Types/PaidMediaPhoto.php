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

class PaidMediaPhoto
{

    /** @var string|mixed $type Type of the paid media, always “photo” */
    public string $type = "photo";

    /** @var PhotoSize[] $photo The photo */
    public array $photo;

    /**
     * default constructor
     *
     * @param array $paidMediaPhoto
     */
    public function __construct(protected array $paidMediaPhoto)
    {
        $this->type = $this->paidMediaPhoto['type'];
        $this->photo = array_map(
            fn($p) => new PhotoSize($p),
            $this->paidMediaPhoto['photo']
        );
    }

}