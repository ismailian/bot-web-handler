<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Interfaces\IValidator;
use TeleBot\System\Telegram\Types\IncomingInlineQuery;

#[Attribute(Attribute::TARGET_METHOD)]
class InlineQuery implements IEvent
{

    /**
     * default constructor
     *
     * @param bool $allowEmpty capture empty query
     * @param IValidator|null $Validator query validator
     */
    public function __construct(public bool $allowEmpty = true, public ?IValidator $Validator = null) {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool|IncomingInlineQuery
    {
        if (!isset($event['inline_query'])) return false;
        if (!$this->allowEmpty && empty($event['inline_query']['query'])) return false;
        if ($this->Validator && !$this->Validator->isValid($event['inline_query']['query'])) return false;

        return new IncomingInlineQuery($event['inline_query']);
    }
}