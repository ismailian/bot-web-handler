<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Validators;

use TeleBot\System\Interfaces\IValidator;

class UrlValidator implements IValidator
{

    /**
     * default constructor
     *
     * @param string|null $scheme
     * @param string|null $host
     * @param string|null $path
     */
    public function __construct(
        public ?string $scheme = null,
        public ?string $host = null,
        public ?string $path = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function isValid(mixed $data): bool
    {
        $urlInfo = parse_url($data);
        $scheme = ($this->scheme && $this->scheme !== $urlInfo['scheme']);
        $host = ($this->host && $this->host !== $urlInfo['host']);
        $path = ($this->path && $this->path !== $urlInfo['path']);

        if (($scheme || $host || $path)) return false;
        return true;
    }
}