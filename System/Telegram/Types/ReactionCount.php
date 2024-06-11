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

class ReactionCount
{

    /** @var ReactionType $reactionType reaction type */
    public ReactionType $reactionType;

    /** @var int $totalCount Number of times the reaction was added */
    public int $totalCount;

    /**
     * default constructor
     *
     * @param array $reactionCount
     */
    public function __construct(protected readonly array $reactionCount)
    {
        $this->totalCount = $this->reactionCount['total_count'];
        $this->reactionType = new ReactionType($this->reactionCount['reaction_type']);
    }

}