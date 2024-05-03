<?php

namespace TeleBot\System\Types;

use DateTime;

class Message
{

    /** @var int $id message id */
    public int $id;

    /** @var DateTime $date message date */
    public DateTime $date;

    /** @var string|null $caption caption */
    public ?string $caption = null;

    /** @var From|null $from message sender */
    public ?From $from = null;

    /** @var Chat|null $chat conversation details */
    public ?Chat $chat = null;

    /** @var array|null $entities message entities */
    public ?array $entities = null;

    /** @var RepliedTo|null $replyTo original message of context reply */
    public ?RepliedTo $replyTo = null;

    /** @var Forward|null $forward message source */
    public ?Forward $forward = null;

    /** @var string|null message text */
    public ?string $text = null;

    /**
     * default constructor
     *
     * @param array $message
     */
    public function __construct(array $message)
    {
        /**
         * photo
         * video
         * audio
         * voice
         * document
         * animation
         * contact
         */

        try {
            $this->id = (int)$message['message_id'];
            $this->date = new DateTime(date('Y-m-d H:i:s T', $message['date']));
            $this->text = $message['text'] ?? null;
            $this->caption = $message['caption'] ?? null;
            $this->entities = array_map(
                fn($e) => new Entity($e['text'] ?? '', $e),
                ($message['entities'] ?? [])
            );

            $this->from = new From($message['from']);
            $this->chat = new Chat($message['chat']);

            if (array_key_exists('reply_to_message', $message)) {
                $this->replyTo = new RepliedTo($message['reply_to_message']);
            }

            if (array_intersect(['forward_from', 'forward_from_chat'], array_keys($message))) {
                $this->forward = new Forward($message);
            }
        } catch (\Exception $ex) {}
    }

}