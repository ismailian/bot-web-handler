<?php

namespace TeleBot\System\Telegram\Enums;

enum MemberStatus
{
    const OWNER = 'creator';
    const ADMIN = 'administrator';
    const MEMBER = 'member';
    const RESTRICTED = 'restricted';
    const BANNED = 'kicked';
    const LEFT = 'left';
}