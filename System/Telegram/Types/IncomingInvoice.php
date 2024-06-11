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

class IncomingInvoice
{

    /** @var string $title product name */
    public string $title;

    /** @var string $description product description */
    public string $description;

    /** @var string $startParameter bot deep-linking parameter that can be used to generate this invoice */
    public string $startParameter;

    /** @var string $currency currency */
    public string $currency;

    /** @var int $totalAmount total price */
    public int $totalAmount;

    /**
     * default constructor
     *
     * @param array $incomingInvoice
     */
    public function __construct(protected readonly array $incomingInvoice)
    {
        $this->title = $incomingInvoice['title'];
        $this->description = $incomingInvoice['description'];
        $this->startParameter = $incomingInvoice['start_parameter'];
        $this->currency = $incomingInvoice['currency'];
        $this->totalAmount = $incomingInvoice['total_amount'];
    }

}