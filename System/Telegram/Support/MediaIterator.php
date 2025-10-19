<?php

namespace TeleBot\System\Telegram\Support;

use Exception;
use TeleBot\System\Telegram\Enums\PassBy;

trait MediaIterator
{

    /** @var string|null $variable variable name to the media objects */
    protected ?string $variable = null;

    /**
     * Loop through media objects
     *
     * @param callable $callback callback to handle the current object
     * @param PassBy $passBy whether to pass the object by reference or value
     * @return void
     * @throws Exception
     */
    public function each(callable $callback, PassBy $passBy = PassBy::Value): void
    {
        if (empty($this->variable) || empty($this->{$this->variable})) {
            throw new Exception('invalid media objects: ' . $this->variable);
        }

        foreach ($this->{$this->variable} as $mediaObject) {
            if ($passBy === PassBy::Value) {
                $callback(clone $mediaObject);
                continue;
            }
            $callback($mediaObject);
        }
    }

}