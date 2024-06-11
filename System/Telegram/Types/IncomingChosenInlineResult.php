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

class IncomingChosenInlineResult
{

    /** @var string $resultId */
    public string $resultId;

    /** @var User $from user who chose a query */
    public User $from;

    /** @var IncomingLocation|null $location */
    public ?IncomingLocation $location = null;

    /** @var string|null $inlineMessageId inline message id */
    public ?string $inlineMessageId = null;

    /** @var string $query */
    public string $query;

    /**
     * default constructor
     *
     * @param array $incomingChosenInlineResult
     */
    public function __construct(protected readonly array $incomingChosenInlineResult)
    {
        $this->from = new User($this->incomingChosenInlineResult['from']);
        $this->resultId = $this->incomingChosenInlineResult['result_id'];
        $this->query = $this->incomingChosenInlineResult['query'];

        if (array_key_exists('inline_message_id', $this->incomingChosenInlineResult['inline_message_id'])) {
            $this->inlineMessageId = $this->incomingChosenInlineResult['inline_message_id'];
        }

        if (array_key_exists('location', $this->incomingChosenInlineResult)) {
            $this->location = new IncomingLocation($this->incomingChosenInlineResult['location']);
        }
    }

}