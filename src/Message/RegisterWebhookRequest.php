<?php

namespace DigiTickets\Dojo\Message;

use DateInterval;
use DateTime;
use DigiTickets\Stripe\Lib\DomainNameExtractor;
use Stripe\StripeClient;

class RegisterWebhookRequest extends AbstractDojoRequest
{
    /**
     * @return array
     *
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('notifyUrl');

        $data = [];
        $data['events'] = ["payment_intent.status_updated"];
        $data['url'] = $this->getNotifyUrl();
        $data['description'] = 'Omnipay Status Webhook';
        return $data;
    }

    /**
     * @param mixed $data The data to send
     *
     * @return RegisterWebhookResponse
     */
    public function sendData($data)
    {
        // Check for existing webhook subscription
        $existingWebhook = $this->getExistingWebhook();
        if ($existingWebhook) {
            return $this->response = new RegisterWebhookResponse($this, $existingWebhook);
        }

        // Otherwise create a new webhook subscription
        $json = json_encode($data);
        $httpResponse = $this->httpClient->post(
            $this->getEndpoint().self::PATH_REGISTER_WEBHOOK,
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $this->getApiKey(),
                'version' => $this->getApiVersion(),
            ],
            $json
        )->send();
        $responseData = $httpResponse->json();

        return $this->response = new RegisterWebhookResponse($this, $responseData);
    }

    private function getExistingWebhook(): array
    {
        $httpResponse = $this->httpClient->get(
            $this->getEndpoint().self::PATH_REGISTER_WEBHOOK,
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $this->getApiKey(),
                'version' => $this->getApiVersion(),
            ]
        )->send();
        $responseData = $httpResponse->json();

        $existingWebhook = [];
        foreach ($responseData as $hook) {
            $hookUrl = $hook['url'] ?? '';
            if ($hookUrl ===  $this->getNotifyUrl() && empty($existingWebhook)) {
                $existingWebhook = $hook;
            }
        }

        return $existingWebhook;
    }
}
