<?php

namespace TeleBot\System\Telegram\Traits;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MapProp
{

    /** @var string|null $source actual source to fetch from */
    public ?string $source = null;

    /** @var bool $useSelf whether to pass the full data as arg to the type */
    public bool $useSelf = false;

    /**
     * Default constructor
     *
     * @param string $key property name
     * @param string|null $type class to map to
     * @param bool $isArray whether it's an array of objects
     * @param bool $asDateTime convert value to DateTime object
     * @param bool $asEnum convert value to enum
     */
    public function __construct(
        public string  $key,
        public ?string $type = null,
        public bool    $isArray = false,
        public bool    $asDateTime = false,
        public bool    $asEnum = false,
    )
    {
        $this->source = $key;
        if (str_contains($this->key, ':')) {
            [$this->key, $this->source] = explode(':', $this->key, 2);
        }

        $this->useSelf = $this->source === '';
    }

}