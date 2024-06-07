<?php

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
    public function __construct(protected array $incomingInvoice)
    {
        $this->title = $incomingInvoice['title'];
        $this->description = $incomingInvoice['description'];
        $this->startParameter = $incomingInvoice['start_parameter'];
        $this->currency = $incomingInvoice['currency'];
        $this->totalAmount = $incomingInvoice['total_amount'];
    }

}