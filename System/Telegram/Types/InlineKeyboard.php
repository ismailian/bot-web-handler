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

use TeleBot\System\Telegram\Enums\InlineKeyboardType;

class InlineKeyboard
{

    /** @var array collection of buttons */
    protected array $buttons = [];

    /** @var int $max max number of buttons in a row */
    protected int $max = 3;

    /**
     * default constructor
     *
     * @param array|null $inlineKeyboard
     */
    public function __construct(protected readonly ?array $inlineKeyboard = null)
    {
        if ($inlineKeyboard) {
            foreach ($inlineKeyboard as $row) {
                foreach ($row as $button) {
                    $this->buttons[] = $button;
                }
            }
        }
    }

    /**
     * set max number of buttons per row
     *
     * @param int $max
     * @return $this
     */
    public function setRowMax(int $max = 3): InlineKeyboard
    {
        $this->max = $max;
        return $this;
    }

    /**
     * add keyboard button
     *
     * @param string $text
     * @param mixed $value
     * @param InlineKeyboardType $type
     * @return $this
     */
    public function addButton(string $text, mixed $value, InlineKeyboardType $type = InlineKeyboardType::URL): InlineKeyboard
    {
        $this->buttons[] = [
            'text' => $text,
            $type->value => is_array($value) ? json_encode($value, JSON_UNESCAPED_SLASHES) : $value
        ];

        return $this;
    }

    /**
     * get button
     *
     * @param int $rowIndex
     * @param int $buttonIndex
     * @return array|null
     */
    public function getButton(int $rowIndex, int $buttonIndex): ?array
    {
        if (array_key_exists($rowIndex, $this->buttons)) {
            return $this->buttons[$rowIndex][$buttonIndex] ?? null;
        }

        return null;
    }

    /**
     * get a string representation of this InlineKeyboard
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES);
    }

    /**
     * get an array representation of this InlineKeyboard
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

        return ['inline_keyboard' => $rows];
    }

}