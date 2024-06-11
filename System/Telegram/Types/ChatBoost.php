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

class ChatBoost
{

    /** @var string $boostId boost id */
    public string $boostId;

    /** @var DateTime $addDate chat boost date */
    public DateTime $addDate;

    /** @var DateTime $expirationDate expiration date */
    public DateTime $expirationDate;

    /** @var ChatBoostSource $source source of the boost */
    public ChatBoostSource $source;

    /**
     * default constructor
     *
     * @param array $chatBoost
     * @throws Exception
     */
    public function __construct(protected readonly array $chatBoost)
    {
        $this->boostId = $chatBoost['boost_id'];
        $this->addDate = new DateTime(date('Y-m-d H:i:s', $chatBoost['add_date']));
        $this->expirationDate = new DateTime(date('Y-m-d H:i:s', $chatBoost['expiration_date']));
        $this->source = new ChatBoostSource($this->chatBoost['source']);
    }

}