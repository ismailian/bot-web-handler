<?php

namespace TeleBot\System\Telegram\Enums;

enum ChatActions: string
{

    case TYPING = 'typing';

    case UPLOAD_PHOTO = 'upload_photo';

    case UPLOAD_VIDEO = 'upload_video';

    case RECORD_VIDEO = 'record_video';

    case RECORD_VIDEO_NOTE = 'record_video_note';

    case UPLOAD_VIDEO_NOTE = 'upload_video_note';

    case RECORD_VOICE = 'record_voice';

    case UPLOAD_VOICE = 'upload_voice';

    case UPLOAD_DOCUMENT = 'upload_document';

    case CHOOSE_STICKER = 'choose_sticker';

    case FIND_LOCATION = 'find_location';

}
