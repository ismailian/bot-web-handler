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

class ChatBoostSource
{

    /** @var string $source boost source */
    public string $source;

    /** @var User|null $user user who boosted the chat */
    public ?User $user = null;

    /** @var int|null $giveawayMessageId giveaway message id */
    public ?int $giveawayMessageId = null;

    /** @var bool|null $isUnclaimed giveaway completed with no winners */
    public ?bool $isUnclaimed = null;

    /**
     * default constructor
     *
     * @param array $chatBoostSource
     */
    public function __construct(protected readonly array $chatBoostSource)
    {
        $this->source = $this->chatBoostSource['source'];
        $this->giveawayMessageId = $this->chatBoostSource['giveaway_message_id'] ?? null;
        $this->isUnclaimed = $this->chatBoostSource['is_unclaimed'] ?? null;

        if (array_key_exists('user', $this->chatBoostSource)) {
            $this->user = new User($this->chatBoostSource['user']);
        }
    }

}