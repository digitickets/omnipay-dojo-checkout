<?php

namespace DigiTickets\Dojo\Message;

use Omnipay\Common\Message\AbstractResponse;

class RefundResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return !empty($this->data['refundId']);
    }

    public function getTransactionReference(): string
    {
        return $this->data['refundId'] ?? '';
    }

    public function getMessage(): string
    {
        return !empty($this->data['refundId']) ? "OK" : "FAIL";
    }

    public function getCode(): string
    {
        return '';
    }
}
