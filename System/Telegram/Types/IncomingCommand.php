<?php

namespace TeleBot\System\Telegram\Types;

class IncomingCommand extends Entity
{

    /**
     * get command value
     *
     * @return string|null
     */
    public function getCommand(): ?string
    {
        if (empty($this->text)) return null;

        $command = substr($this->text, $this->entity['offset'], $this->entity['length']);
        $encoding = mb_detect_encoding($this->text);
        if ($encoding !== 'ASCII') {
            $text = mb_convert_encoding($this->text, 'UTF-16', $encoding);
            $command = substr($text, $this->entity['offset'] * 2, $this->entity['length'] * 2);
        }

        return trim($command);
    }

    /**
     * get all user-input following the command
     *
     * @return array
     */
    public function getArgs(): array
    {
        $args = str_replace($this->getCommand(), '', $this->text);
        return explode(' ', trim($args));
    }

}