<?php

namespace TeleBot\System\Telegram\Types;

class ShippingAddress
{

    /** @var string $countryCode country code */
    public string $countryCode;

    /** @var string $state state, if applicable */
    public string $state;

    /** @var string $city city */
    public string $city;

    /** @var string $streetLine1 street line 1 */
    public string $streetLine1;

    /** @var string $streetLine2 street line 2 */
    public string $streetLine2;

    /** @var string $postCode address post code */
    public string $postCode;

    /**
     * default constructor
     *
     * @param array $shippingAddress
     */
    public function __construct(protected array $shippingAddress)
    {
        $this->countryCode = $this->shippingAddress['country_code'];
        $this->state = $this->shippingAddress['state'];
        $this->city = $this->shippingAddress['city'];
        $this->streetLine1 = $this->shippingAddress['street_line1'];
        $this->streetLine2 = $this->shippingAddress['street_line2'];
        $this->postCode = $this->shippingAddress['post_code'];
    }
}