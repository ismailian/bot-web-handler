<?php

namespace TeleBot\App\Handlers;

use TeleBot\System\BaseEvent;
use TeleBot\System\Events\Audio;
use TeleBot\System\Events\Photo;
use TeleBot\System\Events\Video;
use TeleBot\System\Events\Voice;
use TeleBot\System\Types\IncomingAudio;
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

}