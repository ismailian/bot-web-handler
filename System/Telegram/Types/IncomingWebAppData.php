<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Types;

class IncomingWebAppData
{

    /** @var string $data The data. Be aware that a bad client can send arbitrary data in this field. */
    public string $data;

    /** @var string $buttonText Text of the web_app keyboard button from which the Web App was opened. Be aware that a bad client can send arbitrary data in this field. */
    public string $buttonText;

    /**
     * default constructor
     *
     * @param array $incomingWebAppData
     */
    public function __construct(protected readonly array $incomingWebAppData)
    {
        $this->data = $this->incomingWebAppData['data'];
        $this->buttonText = $this->incomingWebAppData['button_text'];
    }

}