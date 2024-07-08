<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Enums;

enum InlineKeyboardType: string
{
    case URL = 'url';
    case PAY = 'pay';
    case WEB_APP = 'web_app';
    case LOGIN_URL = 'login_url';
    case CALLBACK_GAME = 'callback_game';
    case CALLBACK_DATA = 'callback_data';
    case SWITCH_INLINE_QUERY = 'switch_inline_query';
    case SWITCH_INLINE_QUERY_CHOSEN_CHAT = 'switch_inline_query_chosen_chat';
    case SWITCH_INLINE_QUERY_CURRENT_CHAT = 'switch_inline_query_current_chat';
}
