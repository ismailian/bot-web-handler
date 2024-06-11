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

use DateTime;
use Exception;

class Origin
{

    /** @var ?int $id */
    public ?int $id = null;

    /** @var DateTime $date message date */
    public DateTime $date;

    /** @var string $type origin type */
    public string $type = 'user';

    /** @var string|null $signature author signature */
    public ?string $signature = null;

    /** @var Chat|null $chat chat */
    public ?Chat $chat = null;

    /** @var User|null $from message sender */
    public ?User $from = null;

    /**
     * default constructor
     *
     * @param array $origin
     * @throws Exception
     */
    public function __construct(protected readonly array $origin)
    {
        $this->id = $this->origin['message_id'] ?? null;
        $this->date = new DateTime(date('Y-m-d H:i:s T', $this->origin['date']));

        $this->type = $this->origin['type'];
        $this->signature = $this->origin['author_signature'] ?? null;

        if ($this->type == 'user') {
            $this->from = new User($this->origin['sender_user']);
        } else {
            $this->chat = new Chat($this->origin['chat']);
        }
    }

}