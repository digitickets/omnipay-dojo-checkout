<?php

namespace DigiTickets\Dojo\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    const SUCCESS_STATUS = 'Captured';
    const FAILED_STATUSES = [
        'Reversed',
        'Refunded',
        'Cancelled',
    ];
    const PENDING_STATUSES = [
        'Created',
        'Authorized',
    ];

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data['data']["paymentStatus"]) && $this->data['data']["paymentStatus"] == self::SUCCESS_STATUS;
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        return !isset($this->data['data']["paymentStatus"]) || in_array($this->data['data']["paymentStatus"], self::FAILED_STATUSES);
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return isset($this->data['data']["paymentStatus"]) && in_array($this->data['data']["paymentStatus"], self::PENDING_STATUSES);
    }

    /**
     * this is the gateways transaction ID (paymentIntentID)
     *
     * @return string|null
     */
    public function getTransactionReference()
    {
        return $this->data['data']["paymentIntentId"] ?? null;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->data['data']["paymentStatus"] ?? null;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return '';
    }
}
