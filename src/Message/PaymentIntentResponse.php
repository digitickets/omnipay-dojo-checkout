<?php

namespace DigiTickets\Dojo\Message;

use Omnipay\Common\Message\AbstractResponse;

class PaymentIntentResponse extends AbstractResponse
{
    const CHECKOUT_URL = "https://pay.dojo.tech/checkout/";

    /**
     * @return string|null
     */
    public function getPaymentIntentId()
    {
        $json = $this->getData();

        return $json['id'] ?? '';
    }

    public function getCheckoutUrl()
    {
        return self::CHECKOUT_URL . $this->getPaymentIntentId();
    }

    /**
     * This should be the paymentIntentID
     *
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->getPaymentIntentId();
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        $json = $this->getData();

        return $json &&
            is_array($json) &&
            !empty($json['id']);
    }
}
