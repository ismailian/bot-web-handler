<?php

namespace TeleBot\System\Core\Enums;

enum RuntimeType
{

    /** @var string value indicating that the context is a regular http request */
    case REQUEST;

    /** @var string value indicating that the context is a telegram event */
    case TELEGRAM;

}
