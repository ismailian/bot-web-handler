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

class IncomingCommand extends MessageEntity
{

    /**
     * Default constructor
     *
     * @param string $text
     * @param array $entity
     * @param array $args
     */
    public function __construct(string $text, array $entity, public array $args = [])
    {
        parent::__construct($text, $entity);

        $argKeys = $this->args;
        $argValues = str_replace($this->getCommand(), '', $this->text);
        if (empty($argValues)) return [];

        $this->args = $argValues = explode(' ', trim($argValues));
        if (!empty($argKeys)) {
            $this->args = [];
            foreach ($argKeys as $index => $argKey) {
                $this->args[$argKey] = $argValues[$index] ?? null;
            }
        }

        return true;
    }

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
     * Get command arg
     *
     * @param int|string $key arg key or index
     * @return string|null returns value or null
     */
    public function getArg(int|string $key): ?string
    {
        return $this->args[$key] ?? null;
    }

    /**
     * Get all the text after the command
     *
     * @return string
     */
    public function getRawArgs(): string
    {
        return trim(str_replace($this->getCommand(), '', $this->text));
    }

}