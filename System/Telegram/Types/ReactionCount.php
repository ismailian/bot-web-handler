<?php

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
    public function __construct(protected array $reactionCount)
    {
        $this->totalCount = $this->reactionCount['total_count'];
        $this->reactionType = new ReactionType($this->reactionCount['reaction_type']);
    }

}