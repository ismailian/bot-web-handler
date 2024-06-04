<?php

namespace TeleBot\System\Telegram\Types;

class IncomingSuccessfulPayment
{

    /** @var string $currency payment currency */
    public string $currency = 'USD';

    /** @var int $totalAmount total amount paid */
    public int $totalAmount;

    /** @var string|array $invoicePayload invoice payload */
    public string|array $invoicePayload;

    /** @var string|null $shippingOptionId shipping option */
    public ?string $shippingOptionId = null;

    /** @var object|null $orderInfo Order information provided by the user */
    public ?object $orderInfo = null;

    /** @var string $telegramPaymentChargeId Telegram payment identifier */
    public string $telegramPaymentChargeId;

    /** @var string $providerPaymentChargeId Provider payment identifier */
    public string $providerPaymentChargeId;

    /**
     * default constructor
     *
     * @param array $successfulPayment
     */
    public function __construct(protected array $successfulPayment)
    {
        $this->currency = $this->successfulPayment['currency'];
        $this->totalAmount = $this->successfulPayment['total_amount'];
        $this->invoicePayload = $this->successfulPayment['invoice_payload'];
        $this->shippingOptionId = $this->successfulPayment['shipping_option_id'] ?? null;
        $this->telegramPaymentChargeId = $this->successfulPayment['telegram_payment_charge_id'];
        $this->providerPaymentChargeId = $this->successfulPayment['provider_payment_charge_id'];
        if (array_key_exists('order_info', $this->successfulPayment)) {
            $this->orderInfo = (object) $this->successfulPayment['order_info'];
        }

        /** convert invoice payload to array if json */
        if (($json = json_decode($this->invoicePayload, true))) {
            $this->invoicePayload = $json;
        }
    }

    /**
     * get normalized amount
     *
     * @param string|null $prefix
     * @return float|string
     */
    public function getNormalizedAmount(string $prefix = null): float|string
    {
        $normalized = ($this->totalAmount / 100);
        return $prefix ? $prefix . $normalized : $normalized;
    }

}