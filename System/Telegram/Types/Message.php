<?php

namespace TeleBot\System\Telegram\Types;

use DateTime;

class Message
{

    /** @var int $id message id */
    public int $id;

    /** @var DateTime $date message date */
    public DateTime $date;

    /** @var string|null $messageThreadId supergroup thread id */
    public ?string $messageThreadId;

    /** @var From|null $from message sender */
    public ?From $from = null;

    /** @var Chat|null $chat conversation details */
    public ?Chat $chat = null;

    /** @var array|null $entities message entities */
    public ?array $entities = null;

    /** @var string|null $caption caption */
    public ?string $caption = null;

    /** @var Entity[]|null $captionEntities list of caption entities */
    public ?array $captionEntities = null;

    /** @var InlineKeyboard|null $replyMarkup inline keyboard */
    public ?InlineKeyboard $replyMarkup = null;

    /** @var RepliedTo|null $replyTo original message of context reply */
    public ?RepliedTo $replyTo = null;

    /** @var Forward|null $forward message source */
    public ?Forward $forward = null;

    /** @var string|null message text */
    public ?string $text = null;

    /** @var IncomingPhoto|null $photo photo message */
    public ?IncomingPhoto $photo = null;

    /** @var IncomingVideo|null $video video message */
    public ?IncomingVideo $video = null;

    /** @var IncomingVideoNote|null $videoNote video note message */
    public ?IncomingVideoNote $videoNote = null;

    /** @var IncomingAudio|null $audio audio message */
    public ?IncomingAudio $audio = null;

    /** @var IncomingVoice|null $voice voice note message */
    public ?IncomingVoice $voice = null;

    /** @var IncomingDocument|null $document document message */
    public ?IncomingDocument $document = null;

    /** @var IncomingContact|null $contact contact message */
    public ?IncomingContact $contact = null;

    /** @var IncomingAnimation|null $animation animation message */
    public ?IncomingAnimation $animation = null;

    /** @var IncomingLocation|null $location location message */
    public ?IncomingLocation $location = null;

    /** @var IncomingDice|null $dice dice message */
    public ?IncomingDice $dice = null;

    /** @var IncomingSticker|null $sticker sticker message */
    public ?IncomingSticker $sticker = null;

    /** @var IncomingStory|null $story story message */
    public ?IncomingStory $story = null;

    /** @var IncomingGame|null $game game message */
    public ?IncomingGame $game = null;

    /** @var IncomingPoll|null $poll poll message */
    public ?IncomingPoll $poll = null;

    /** @var IncomingVenue|null $venue venue message */
    public ?IncomingVenue $venue = null;

    /** @var IncomingInvoice|null $invoice invoice message */
    public ?IncomingInvoice $invoice = null;

    /** @var IncomingSuccessfulPayment|null $successfulPayment */
    public ?IncomingSuccessfulPayment $successfulPayment = null;

    /**
     * default constructor
     *
     * @param array $message
     */
    public function __construct(array $message)
    {
        try {
            $this->id = (int)$message['message_id'];
            $this->date = new DateTime(date('Y-m-d H:i:s T', $message['date']));
            $this->text = $message['text'] ?? null;
            $this->caption = $message['caption'] ?? null;
            $this->entities = array_map(
                fn($e) => new Entity($e['text'] ?? '', $e),
                ($message['entities'] ?? [])
            );

            $this->chat = new Chat($message['chat']);

            /** <From> */
            if (array_key_exists('from', $message)) {
                $this->from = new From($message['from']);
            }

            /** <ReplyToMessage> */
            if (array_key_exists('reply_to_message', $message)) {
                $this->replyTo = new RepliedTo($message['reply_to_message']);
            }

            /** <FrowardFrom|ForwardFromChat> */
            if (array_intersect(['forward_from', 'forward_from_chat'], array_keys($message))) {
                $this->forward = new Forward($message);
            }
        } catch (\Exception $ex) {}
    }

}