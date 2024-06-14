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

use DateTime;
use Exception;

class GiveawayWinners
{

    /** @var Chat $chat The chat that created the giveaway */
    public Chat $chat;

    /** @var int $giveawayMessageId Identifier of the message with the giveaway in the chat */
    public int $giveawayMessageId;

    /** @var DateTime $winnersSelectionDate when winners of the giveaway were selected */
    public DateTime $winnersSelectionDate;

    /** @var int $winnerCount Total number of winners in the giveaway */
    public int $winnerCount;

    /** @var User[] $winners List of up to 100 winners of the giveaway */
    public array $winners;

    /** @var int|null $additionalChatCount
     * The number of other chats the user had to join in order to be eligible for the giveaway
     */
    public ?int $additionalChatCount = null;

    /** @var int|null $premiumSubscriptionMonthCount
     * The number of months the Telegram Premium subscription won from the giveaway will be active for
     */
    public ?int $premiumSubscriptionMonthCount = null;

    /** @var int|null $unclaimedPrizeCount Number of undistributed prizes */
    public ?int $unclaimedPrizeCount = null;

    /** @var bool|null $onlyNewMembers
     * True, if only users who had joined the chats after the giveaway started were eligible to win
     */
    public ?bool $onlyNewMembers = null;

    /** @var bool|null $wasRefunded
     * True, if the giveaway was canceled because the payment for it was refunded
     */
    public ?bool $wasRefunded = null;

    /** @var string|null $prizeDescription Description of additional giveaway prize */
    public ?string $prizeDescription = null;

    /**
     * default constructor
     *
     * @param array $giveawayWinners
     * @throws Exception
     */
    public function __construct(protected readonly array $giveawayWinners)
    {
        $this->chat = new Chat($this->giveawayWinners['chat']);
        $this->giveawayMessageId = $this->giveawayWinners['giveaway_message_id'];
        $this->winnersSelectionDate = new DateTime(
            date('Y-m-d H:i:s', $this->giveawayWinners['selection_date'])
        );

        $this->winnerCount = $this->giveawayWinners['winners_count'];
        $this->winners = array_map(fn($winner) => new User($winner),
            $this->giveawayWinners['winners']
        );

        $this->additionalChatCount = $this->giveawayWinners['additional_chat_count'] ?? null;
        $this->premiumSubscriptionMonthCount = $this->giveawayWinners['premium_subscription_month_count'] ?? null;
        $this->unclaimedPrizeCount = $this->giveawayWinners['unclaimed_prize_count'] ?? null;
        $this->onlyNewMembers = $this->giveawayWinners['only_new_members'] ?? null;
        $this->wasRefunded = $this->giveawayWinners['was_refunded'] ?? null;
        $this->prizeDescription = $this->giveawayWinners['prize_description'] ?? null;
    }

}