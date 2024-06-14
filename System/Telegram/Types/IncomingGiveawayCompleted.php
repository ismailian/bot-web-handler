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

class IncomingGiveawayCompleted
{

    /** @var int $winnerCount Number of winners in the giveaway */
    public int $winnerCount;

    /** @var int|null $unclaimedPrizeCount Number of undistributed prizes */
    public ?int $unclaimedPrizeCount = null;

    /** @var IncomingMessage|null $giveawayMessage */
    public ?IncomingMessage $giveawayMessage = null;

    /**
     * default constructor
     *
     * @param array $incomingGiveawayCompleted
     */
    public function __construct(protected readonly array $incomingGiveawayCompleted)
    {
        $this->winnerCount = $this->incomingGiveawayCompleted['winner_count'];
        $this->unclaimedPrizeCount = $this->incomingGiveawayCompleted['unclaimed_prize_count'] ?? null;

        if (array_key_exists('giveaway_message', $this->incomingGiveawayCompleted)) {
            $this->giveawayMessage = new IncomingMessage(
                $this->incomingGiveawayCompleted['giveaway_message'],
            );
        }
    }

}