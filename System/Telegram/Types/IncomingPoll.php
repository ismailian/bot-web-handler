<?php

namespace TeleBot\System\Telegram\Types;

use TeleBot\System\Telegram\Enums\PollType;
use TeleBot\System\Telegram\Events\PollAnswer;

class IncomingPoll
{

    /** @var string $id unique poll id */
    public string $id;

    /** @var string $question poll question */
    public string $question;

    /** @var Entity[]|null $questionEntities question entities */
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

    /** @var Entity[]|null $explanationEntities explanation entities */
    public ?array $explanationEntities = null;

    /** @var int|null $openPeriod amount of time poll will be opened */
    public ?int $openPeriod = null;

    /** @var int|null $closeDate  */
    public ?int $closeDate = null;

    /**
     * default constructor
     */
    public function __construct()
    {
        // todo: fill properties
    }
}