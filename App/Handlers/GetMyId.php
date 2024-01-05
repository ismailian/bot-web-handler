<?php

namespace TeleBot\App\Handlers;

use TeleBot\System\BaseEvent;
use TeleBot\System\Events\Audio;
use TeleBot\System\Events\Contact;
use TeleBot\System\Events\Dice;
use TeleBot\System\Events\Document;
use TeleBot\System\Events\Photo;
use TeleBot\System\Events\Video;
use TeleBot\System\Events\Voice;
use TeleBot\System\Types\IncomingAudio;
use TeleBot\System\Types\IncomingContact;
use TeleBot\System\Types\IncomingDice;
use TeleBot\System\Types\IncomingDocument;
use TeleBot\System\Types\IncomingPhoto;
use TeleBot\System\Types\IncomingVideo;
use TeleBot\System\Types\IncomingVoice;

class GetMyId extends BaseEvent
{

    /**
     * handle incoming photo messages
     *
     * @param IncomingPhoto $photo
     * @return void
     */
    #[Photo]
    public function handlePhoto(IncomingPhoto $photo): void {}

    /**
     * handle incoming video messages
     *
     * @param IncomingVideo $video
     * @return void
     */
    #[Video]
    public function handleVideo(IncomingVideo $video): void {}

    /**
     * handle incoming audio messages
     *
     * @param IncomingAudio $audio
     * @return void
     */
    #[Audio]
    public function handleAudio(IncomingAudio $audio): void {}

    /**
     * handle incoming voice messages
     *
     * @param IncomingVoice $voice
     * @return void
     */
    #[Voice]
    public function handleVoice(IncomingVoice $voice): void {}

    /**
     * handle incoming document messages
     *
     * @param IncomingDocument $document
     * @return void
     */
    #[Document]
    public function handleDocument(IncomingDocument $document): void {}

    /**
     * handle incoming contact messages
     *
     * @param IncomingContact $contact
     * @return void
     */
    #[Contact]
    public function handleContact(IncomingContact $contact): void {}

    /**
     * handle incoming dice messages
     *
     * @param IncomingDice $dice
     * @return void
     */
    #[Dice]
    public function handleDice(IncomingDice $dice): void {}

}