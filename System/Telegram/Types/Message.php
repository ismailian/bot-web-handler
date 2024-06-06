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
    public function __construct(protected array $message)
    {
        try {
            $this->id = (int)$this->message['message_id'];
            $this->date = new DateTime(date('Y-m-d H:i:s T', $this->message['date']));
            $this->text = $this->message['text'] ?? null;
            $this->caption = $this->message['caption'] ?? null;
            $this->entities = array_map(
                fn($e) => new Entity($e['text'] ?? '', $e),
                ($this->message['entities'] ?? [])
            );

            $this->chat = new Chat($this->message['chat']);

            /** <From> */
            if (array_key_exists('from', $this->message)) {
                $this->from = new From($this->message['from']);
            }

            /** <ReplyToMessage> */
            if (array_key_exists('reply_to_message', $this->message)) {
                $this->replyTo = new RepliedTo($this->message['reply_to_message']);
            }

            /** <FrowardFrom|ForwardFromChat> */
            if (array_intersect(['forward_from', 'forward_from_chat'], array_keys($this->message))) {
                $this->forward = new Forward($this->message);
            }

            /** <Photo> */
            if (array_key_exists('photo', $this->message)) {
                $this->photo = new IncomingPhoto($this->message['photo']);
            }

            /** <Video> */
            if (array_key_exists('video', $this->message)) {
                $this->video = new IncomingVideo($this->message['video']);
            }

            /** <VideoNote> */
            if (array_key_exists('video_note', $this->message)) {
                $this->videoNote = new IncomingVideoNote($this->message['video_note']);
            }

            /** <Voice> */
            if (array_key_exists('voice', $this->message)) {
                $this->voice = new IncomingVoice($this->message['voice']);
            }

            /** <Audio> */
            if (array_key_exists('audio', $this->message)) {
                $this->audio = new IncomingAudio($this->message['audio']);
            }

            /** <Document> */
            if (array_key_exists('document', $this->message)) {
                $this->document = new IncomingDocument($this->message['document']);
            }

            /** <Contact> */
            if (array_key_exists('contact', $this->message)) {
                $this->contact = new IncomingContact($this->message['contact']);
            }

            /** <Dice> */
            if (array_key_exists('dice', $this->message)) {
                $this->dice = new IncomingDice($this->message['dice']);
            }

            /** <Animation> */
            if (array_key_exists('animation', $this->message)) {
                $this->animation = new IncomingAnimation($this->message['animation']);
            }

            /** <Sticker> */
            if (array_key_exists('sticker', $this->message)) {
                $this->sticker = new IncomingSticker($this->message['sticker']);
            }
        } catch (\Exception $ex) {}
    }

}