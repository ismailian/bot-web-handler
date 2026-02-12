<?php /** @noinspection SpellCheckingInspection */

/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Types;

class IncomingUrl extends MessageEntity
{

    /**
     * get full message text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * get parsed url
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        if (empty($this->text)) return null;

        $encoding = mb_detect_encoding($this->text);
        if ($encoding !== 'ASCII') {
            $text = mb_convert_encoding($this->text, 'UTF-16', $encoding);
            $text = substr($text, $this->offset * 2, $this->length * 2);
            return mb_convert_encoding($text, 'UTF-8', 'UTF-16');
        }

        return substr($this->text, $this->offset, $this->length);
    }

    /**
     * stringified url object
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getUrl() ?? "";
    }

}