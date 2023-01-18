<?php

namespace DigiTickets\Dojo;

use DigiTickets\Dojo\Message\CompletePurchaseRequest;
use DigiTickets\Dojo\Message\PaymentIntentRequest;
use DigiTickets\Dojo\Message\PaymentIntentResponse;
use DigiTickets\Dojo\Message\PurchaseRequest;
use DigiTickets\Dojo\Message\RefundRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Dojo (Checkout)';
    }

    public function getDefaultParameters(): array
    {
        return [
            'apiKey' => '',
            'webhookSecretKey' => '',
        ];
    }

    public function purchase(array $parameters = []): RequestInterface
    {
        // TODO webhooks
        //$request = $this->createRequest(RegisterWebhookRequest::class, $parameters);
        //$response = $request->send();

        // If the driver is configured to register a web hook and the response was unsuccessful
        //if(!empty($request->getNotifyUrl()) && !$response->isSuccessful()) {
        //    throw new Exception("stripe webhook was not registered");
        //}

        $request = $this->createRequest(PaymentIntentRequest::class, $parameters);
        /** @var PaymentIntentResponse $response */
        $response = $request->send();

        $parameters['checkoutUrl'] = $response->getCheckoutUrl();
        $parameters['transactionReference'] = $response->getTransactionReference();

        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * Should be called only when a status updated webhook arrives - payment_intent.status_updated (not when the customer is redirected back to your application)
     *
     * @param array $parameters
     *
     * @return RequestInterface
     */
    public function acceptNotification(array $parameters = []): RequestInterface
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    public function refund(array $parameters = []): RequestInterface
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }
}
