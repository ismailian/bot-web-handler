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

class IncomingPoll
{

    /** @var string $id unique poll id */
    public string $id;

    /** @var string $question poll question */
    public string $question;

    /** @var MessageEntity[]|null $questionEntities question entities */
    public ?array $questionEntities = null;

    /** @var PollOption[]|null $options list of poll options */
    public ?array $options = null;

    /** @var int $totalVoterCount total number of votes */
    public int $totalVoterCount;

    /** @var bool $isClosed is poll closed */
    public bool $isClosed;

    /** @var bool $isAnonymous is poll anonymous */
    public bool $isAnonymous;

    /** @var string $type poll type */
    public string $type;

    /** @var bool $allowMultipleAnswers allow multiple answers */
    public bool $allowMultipleAnswers;

    /** @var int|null $correctOptionId correct option id */
    public ?int $correctOptionId = null;

    /** @var string|null $explanation text to show when user chooses incorrect answer */
    public ?string $explanation = null;

    /** @var MessageEntity[]|null $explanationEntities explanation entities */
    public ?array $explanationEntities = null;

    /** @var int|null $openPeriod amount of time poll will be opened */
    public ?int $openPeriod = null;

    /** @var DateTime|null $closeDate  */
    public ?DateTime $closeDate = null;

    /**
     * default constructor
     *
     * @param array $incomingPoll
     * @throws Exception
     */
    public function __construct(protected readonly array $incomingPoll)
    {
        $this->id = $this->incomingPoll['id'];
        $this->question = $this->incomingPoll['question'];

        if (array_key_exists('question_entities', $this->incomingPoll)) {
            $this->questionEntities = array_map(
                fn($e) => new MessageEntity($this->question, $e),
                $this->incomingPoll['question_entities']
            );
        }

        $this->options = array_map(fn($o) => new PollOption($o), $this->incomingPoll['options']);
        $this->type = $this->incomingPoll['type'];
        $this->isClosed = $this->incomingPoll['is_closed'];
        $this->isAnonymous = $this->incomingPoll['is_anonymous'];
        $this->openPeriod = $this->incomingPoll['open_period'] ?? null;
        $this->totalVoterCount = $this->incomingPoll['total_voter_count'];
        $this->correctOptionId = $this->incomingPoll['correct_option_id'] ?? null;
        $this->allowMultipleAnswers = $this->incomingPoll['allows_multiple_answers'];

        if (array_key_exists('explanation', $this->incomingPoll)) {
            $this->explanation = $this->incomingPoll['explanation'];
            $this->explanationEntities = array_map(
                fn($e) => new MessageEntity($this->explanation, $e),
                $incomingPoll['explanation_entities']
            );
        }

        if (array_key_exists('close_date', $this->incomingPoll)) {
            $this->closeDate = new DateTime(date('Y-m-d H:i:s', strtotime($this->incomingPoll['close_date'])));
        }
    }
}