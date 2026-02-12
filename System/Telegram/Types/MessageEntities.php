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

use Traversable;
use ArrayIterator;

class MessageEntities implements \IteratorAggregate
{

    /** @var array|MessageEntity[] $entities list of entities */
    private array $entities = [];

    /**
     * default constructor
     *
     * @param array $message
     * @param string $entitiesKey
     */
    public function __construct(array $message, string $entitiesKey = 'entities')
    {
        $this->entities = array_map(
            fn($e) => new MessageEntity($message['text'], ...$e),
            $message[$entitiesKey]
        );
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->entities);
    }
}