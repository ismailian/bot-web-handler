<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram;

use GuzzleHttp\Client;
use TeleBot\System\Telegram\Traits\Catchable;
use TeleBot\System\Telegram\Traits\Extensions;
use TeleBot\System\Telegram\Traits\HttpClient;
use TeleBot\System\Telegram\Traits\Methods\Copy;
use TeleBot\System\Telegram\Traits\Methods\Get;
use TeleBot\System\Telegram\Traits\Methods\Send;
use TeleBot\System\Telegram\Traits\Methods\Edit;
use TeleBot\System\Telegram\Traits\Methods\Answer;
use TeleBot\System\Telegram\Traits\Methods\Delete;
use TeleBot\System\Telegram\Traits\Methods\Actions;
use TeleBot\System\Telegram\Traits\Methods\Forward;
use TeleBot\System\Telegram\Traits\Methods\Session;
use TeleBot\System\Telegram\Traits\Methods\Webhook;

class BotApi
{

    /**
     * This class serves as a higher abstraction layer for
     * the functionality implemented in the traits below
     */

    use HttpClient, Catchable, Extensions;

    use Get;
    use Send;
    use Edit;
    use Copy;
    use Delete;
    use Answer;
    use Webhook;
    use Actions;
    use Session;
    use Forward;

    /**
     * default constructor
     */
    public function __construct()
    {
        $this->api = new Client();
        $this->setToken(
            env('TG_BOT_TOKEN')
        );
    }
}