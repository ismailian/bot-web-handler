<?php

namespace TeleBot\System\Telegram\Types;

use DateTime;
use Exception;

class IncomingMessageReactionUpdated
{

    /** @var int $messageId the message being reacted to */
    public int $messageId;

    /** @var User|null $user user that performed the reaction */
    public ?User $user = null;

    /** @var Chat $chat the message parent chat */
    public Chat $chat;

    /** @var Chat|null $actorChat The chat on behalf of which the reaction was changed */
    public ?Chat $actorChat = null;

    /** @var DateTime $date date of the reaction update */
    public DateTime $date;

    /** @var ReactionType[] $oldReactions list of old reaction types */
    public array $oldReactions;

    /** @var ReactionType[] $newReactions list of new reaction types */
    public array $newReactions;

    /**
     * default constructor
     *
     * @param array $incomingMessageReactionUpdated
     * @throws Exception
     */
    public function __construct(protected array $incomingMessageReactionUpdated)
    {
        $this->messageId = $this->incomingMessageReactionUpdated['message_id'];
        $this->chat = new Chat($this->incomingMessageReactionUpdated['chat']);
        $this->date = new DateTime(date('Y-m-d H:i:s', $this->incomingMessageReactionUpdated['date']));

        if (array_key_exists('user', $this->incomingMessageReactionUpdated)) {
            $this->user = new User($this->incomingMessageReactionUpdated['user']);
        }

        if (array_key_exists('actor_chat', $this->incomingMessageReactionUpdated)) {
            $this->actorChat = new Chat($this->incomingMessageReactionUpdated['actor_chat']);
        }

        $this->oldReactions = array_map(
            fn($r) => new ReactionType($r),
            $this->incomingMessageReactionUpdated['old_reactions']
        );

        $this->newReactions = array_map(
            fn($r) => new ReactionType($r),
            $this->incomingMessageReactionUpdated['new_reactions']
        );
    }

}