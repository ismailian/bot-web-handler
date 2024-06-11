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

class ReplyKeyboard
{

    /** @var bool|null $isPersistent is keyboard persistent? */
    public ?bool $isPersistent = null;

    /** @var bool|null $resizeKeyboard resize keyboard? */
    public ?bool $resizeKeyboard = null;

    /** @var bool|null $oneTimeKeyboard one time keyboard? */
    public ?bool $oneTimeKeyboard = null;

    /** @var bool|null $selective selective? */
    public ?bool $selective = null;

    /** @var string|null $inputFieldPlaceholder */
    public ?string $inputFieldPlaceholder = null;

    /** @var array collection of buttons */
    protected array $buttons = [];

    /** @var int $max max number of buttons in a row */
    protected int $max = 3;

    /**
     * set max number of buttons per row
     *
     * @param int $max
     * @return $this
     */
    public function setRowMax(int $max = 3): ReplyKeyboard
    {
        $this->max = $max;
        return $this;
    }

    /**
     * add keyboard button
     *
     * @param string $text
     * @param mixed $value
     * @param string $type
     * @return $this
     */
    public function addButton(string $text, mixed $value, string $type = ReplyKeyboard::URL): ReplyKeyboard
    {
        $this->buttons[] = [];
        return $this;
    }

    /**
     * get an array representation of this ReplyKeyboard
     *
     * @return array
     */
    public function toArray(): array
    {
        $rows = [];
        foreach ($this->buttons as $button) {
            $idx = count($rows) - 1;
            if (count($rows) > 0 && count($rows[$idx]) < $this->max) {
                $rows[$idx][] = $button;
                continue;
            }

            $rows[] = [$button];
        }

        $replyKeyboard = ['keyboard' => $rows];
        if (!is_null($this->selective)) $replyKeyboard['selective'] = $this->selective;
        if (!is_null($this->isPersistent)) $replyKeyboard['is_persistent'] = $this->isPersistent;
        if (!is_null($this->resizeKeyboard)) $replyKeyboard['resize_keyboard'] = $this->resizeKeyboard;
        if (!is_null($this->oneTimeKeyboard)) $replyKeyboard['one_time_keyboard'] = $this->oneTimeKeyboard;
        if (!is_null($this->inputFieldPlaceholder)) $replyKeyboard['input_field_placeholder'] = $this->inputFieldPlaceholder;

        return ['reply_keyboard' => $replyKeyboard];
    }

}