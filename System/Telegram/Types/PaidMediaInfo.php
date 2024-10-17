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

readonly class PaidMediaInfo
{

    /** @var int $starCount The number of Telegram Stars that must be paid to buy access to the media */
    public int $starCount;

    /** @var array $paidMedia Information about the paid media */
    public array $paidMedia;

    /**
     * default constructor
     *
     * @param array $paidMediaInfo
     */
    public function __construct(protected array $paidMediaInfo)
    {
        $this->starCount = $this->paidMediaInfo['star_count'];
        $this->paidMedia = array_map(
            function ($pm) {
                return match ($pm['type']) {
                    'photo' => new PaidMediaPhoto($pm),
                    'video' => new PaidMediaVideo($pm),
                    'preview' => new PaidMediaPreview($pm),
                };
            },
            $this->paidMediaInfo['paid_media']
        );
    }
}