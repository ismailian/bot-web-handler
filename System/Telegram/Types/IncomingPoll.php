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

use DateTime, Exception;
use TeleBot\System\Telegram\Enums\PollType;
use TeleBot\System\Telegram\Traits\MapProp;
use TeleBot\System\Telegram\Support\Hydrator;

class IncomingPoll
{

    /** @var string $id unique poll id */
    #[MapProp('id')]
    public string $id;

    /** @var string $question poll question */
    #[MapProp('question')]
    public string $question;

    /** @var MessageEntities|null $questionEntities question entities */
    public ?MessageEntities $questionEntities = null;

    /** @var PollOption[]|null $options list of poll options */
    #[MapProp('options', PollOption::class, isArray: true)]
    public ?array $options = null;

    /** @var int $totalVoterCount total number of votes */
    #[MapProp('total_voter_count')]
    public int $totalVoterCount;

    /** @var bool $isClosed is poll closed */
    #[MapProp('is_closed')]
    public bool $isClosed;

    /** @var bool $isAnonymous is poll anonymous */
    #[MapProp('is_anonymous')]
    public bool $isAnonymous;

    /** @var PollType $type poll type */
    #[MapProp('type', PollType::class, asEnum: true)]
    public PollType $type;

    /** @var bool $allowMultipleAnswers allow multiple answers */
    #[MapProp('allow_multiple_answers')]
    public bool $allowMultipleAnswers;

    /** @var int|null $correctOptionId correct option id */
    #[MapProp('correct_option_id')]
    public ?int $correctOptionId = null;

    /** @var string|null $explanation text to show when user chooses incorrect answer */
    #[MapProp('explanation')]
    public ?string $explanation = null;

    /** @var MessageEntities|null $explanationEntities explanation entities */
    public ?MessageEntities $explanationEntities = null;

    /** @var int|null $openPeriod amount of time poll will be opened */
    #[MapProp('open_period')]
    public ?int $openPeriod = null;

    /** @var DateTime|null $closeDate */
    #[MapProp('close_date', asDateTime: true)]
    public ?DateTime $closeDate = null;

    /**
     * default constructor
     *
     * @param array $incomingPoll
     * @throws Exception
     */
    public function __construct(array $incomingPoll)
    {
        Hydrator::hydrate($this, $incomingPoll);

        if (array_key_exists('question_entities', $incomingPoll)) {
            $this->questionEntities = new MessageEntities([
                'text' => $incomingPoll['question'],
                'entities' => $incomingPoll['question_entities'],
            ]);
        }

        if (array_key_exists('explanation_entities', $incomingPoll)) {
            $this->explanationEntities = new MessageEntities([
                'text' => $incomingPoll['explanation'],
                'entities' => $incomingPoll['explanation_entities'],
            ]);
        }
    }
}