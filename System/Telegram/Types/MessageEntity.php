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

class MessageEntity
{

    /** @var int $offset entity offset */
    public int $offset;

    /** @var int $length entity length */
    public int $length;

    /** @var string $type entity type */
    public string $type;

    /**
     * default constructor
     *
     * @param string $text
     * @param array $entity
     */
    public function __construct(protected string $text, protected array $entity)
    {
        $this->offset = $this->entity['offset'];
        $this->length = $this->entity['length'];
        $this->type = $this->entity['type'];
    }

}