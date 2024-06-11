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

class IncomingShippingQuery
{

    /** @var string $id shipping query id */
    public string $id;

    /** @var User $from user who sent query */
    public user $from;

    /** @var string $invoicePayload invoice payload */
    public string $invoicePayload;

    /** @var ShippingAddress $shippingAddress shipping address */
    public ShippingAddress $shippingAddress;

    /**
     * default constructor
     *
     * @param array $incomingShippingQuery
     */
    public function __construct(protected readonly array $incomingShippingQuery)
    {
        $this->id = $incomingShippingQuery['id'];
        $this->from = new User($incomingShippingQuery['from']);
        $this->invoicePayload = $incomingShippingQuery['invoice_payload'];
        $this->shippingAddress = new ShippingAddress($incomingShippingQuery['shipping_address']);

        /* convert payload to json if applicable */
        if (($json = json_decode($this->invoicePayload, true))) {
            $this->invoicePayload = $json;
        }
    }

}