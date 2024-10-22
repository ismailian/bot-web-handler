<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Core\Attributes;

use Attribute;

#[Attribute(
    Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE
)]
readonly class Delegate
{

    /**
     * default constructor
     *
     * @param string $delegateTo
     */
    public function __construct(protected string $delegateTo) {}

    /**
     * invoke delegateTo class
     *
     * @return void
     */
    public function __invoke(): void
    {
        (new $this->delegateTo())();
    }

}