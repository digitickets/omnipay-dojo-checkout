<?php

namespace DigiTickets\Dojo\Message;

use Omnipay\Common\Message\AbstractRequest;

abstract class AbstractDojoRequest extends AbstractRequest
{
    const ENDPOINT = 'https://api.dojo.tech';
    const API_VERSION = '2022–04–07';
    const PATH_PAYMENT_INTENT = '/payment-intents/';
    const PATH_PAYMENT_REFUND = '/payment-intents/{paymentIntentId}/refunds';
    const PATH_REGISTER_WEBHOOK = '/webhooks';

    public function getApiVersion() : string
    {
        return self::API_VERSION;
    }

    public function getApiKey(): string
    {
        $prefix = '';
        if ($this->getTestMode()) {
            $prefix = 'sk_sandbox_';
        }

        return $prefix.$this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function getWebhookSecretKey(): string
    {
        return $this->getParameter('webhookSecretKey');
    }

    public function setWebhookSecretKey($value)
    {
        return $this->setParameter('webhookSecretKey', $value);
    }
}
