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

enum MemberStatus
{
    const string OWNER = 'creator';
    const ADMIN = 'administrator';
    const string MEMBER = 'member';
    const string RESTRICTED = 'restricted';
    const string BANNED = 'kicked';
    const string LEFT = 'left';
}