<?php

namespace TeleBot\System\Telegram\Types;

class IncomingPreCheckoutQuery
{

    /** @var string $id query id */
    public string $id;

    /** @var string $currency invoice currency */
    public string $currency = 'USD';

    /** @var int $amount invoice amount */
    public int $amount;

    /** @var string|array $invoicePayload invoice payload */
    public string|array $invoicePayload;

    /** @var From $from From object */
    public From $from;

    /**
     * default constructor
     *
     * @param array $preCheckoutQuery
     */
    public function __construct(protected array $preCheckoutQuery)
    {
        $this->id = $preCheckoutQuery['id'];
        $this->from = new From($preCheckoutQuery['from']);
        $this->currency = $preCheckoutQuery['currency'];
        $this->amount = $preCheckoutQuery['total_amount'];
        $this->invoicePayload = $preCheckoutQuery['invoice_payload'];

        /** convert payload to array if json */
        if (($json = json_decode($this->invoicePayload, true))) {
            $this->invoicePayload = $json;
        }
    }

    /**
     * get normalized price amount
     *
     * @return float
     */
    public function getNormalizedAmount(): float
    {
        return (float) ($this->amount / 100);
    }

}