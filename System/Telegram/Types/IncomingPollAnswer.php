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

class IncomingPollAnswer
{

    /** @var string $pollId poll id */
    public string $pollId;

    /** @var Chat|null $voterChat The chat that changed the answer to the poll, if the voter is anonymous */
    public ?Chat $voterChat = null;

    /** @var User|null $user The user that changed the answer to the poll, if the voter isn't anonymous */
    public ?User $user = null;

    /** @var int[] $optionIds zero-based list of chosen answers */
    public array $optionIds;

    /**
     * default constructor
     *
     * @param array $incomingPollAnswer
     */
    public function __construct(protected readonly array $incomingPollAnswer)
    {
        $this->pollId = $incomingPollAnswer['poll_id'];
        $this->optionIds = $incomingPollAnswer['option_ids'];

        if (array_key_exists('voter_chat', $this->incomingPollAnswer)) {
            $this->voterChat = new Chat($incomingPollAnswer['voter_chat']);
        }

        if (array_key_exists('user_chat', $this->incomingPollAnswer)) {
            $this->user = new User($incomingPollAnswer['user']);
        }
    }

}