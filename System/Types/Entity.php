<?php

namespace TeleBot\System\Types;

class Entity
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