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

class Giveaway
{

    /** @var array $chats The list of chats which the user must join to participate in the giveaway */
    public array $chats;

    /** @var DateTime $winnersSelectionDate
     * when winners of the giveaway will be selected
     */
    public DateTime $winnersSelectionDate;

    /** @var int $winnerCount The number of users which are supposed to be selected as winners of the giveaway */
    public int $winnerCount;

    /** @var bool|null $onlyNewMembers
     * True, if only users who join the chats after the giveaway started should be eligible to win
     */
    public ?bool $onlyNewMembers = null;

    /** @var bool|null $hasPublicWinners True, if the list of giveaway winners will be visible to everyone */
    public ?bool $hasPublicWinners = null;

    /** @var string|null $prizeDescription Description of additional giveaway prize */
    public ?string $prizeDescription = null;

    /** @var array|null $countryCodes
     * A list of two-letter ISO 3166-1 alpha-2 country codes indicating the countries
     * from which eligible users for the giveaway must come
     */
    public ?array $countryCodes = null;

    /** @var int|null $premiumSubscriptionMonthCount
     * The number of months the Telegram Premium subscription won from the giveaway will be active for
     */
    public ?int $premiumSubscriptionMonthCount = null;

    /**
     * default constructor
     *
     * @param array $incomingGiveaway
     * @throws Exception
     */
    public function __construct(protected readonly array $incomingGiveaway)
    {
        $this->chats = array_map(fn($chat) => new Chat($chat), $this->incomingGiveaway['chats']);
        $this->winnersSelectionDate = new DateTime(
            date('Y-m-d H:i:s', $this->incomingGiveaway['winners_selection_date'])
        );

        $this->winnerCount = $this->incomingGiveaway['winners_count'];
        $this->onlyNewMembers = $this->incomingGiveaway['only_new_members'] ?? null;
        $this->hasPublicWinners = $this->incomingGiveaway['has_public_winners'] ?? null;
        $this->prizeDescription = $this->incomingGiveaway['prize_description'] ?? null;
        $this->countryCodes = $this->incomingGiveaway['country_codes'] ?? null;
        $this->premiumSubscriptionMonthCount = $this->incomingGiveaway['premium_subscription_month_count'] ?? null;
    }

}