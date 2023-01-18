<?php

namespace DigiTickets\Dojo\Message;

class RefundRequest extends AbstractDojoRequest
{
    public function getData(): array
    {
        $randomStr = substr(strtr(base64_encode(random_bytes(20)), '+/', '-_'), 0, 20);

        return [
            [
                'amount' => $this->getAmountInteger(),
                'refundReason' => $this->getDescription(),
                'idempotencyKey' => $randomStr,
            ],
        ];
    }

    public function sendData($data) : RefundResponse
    {
        $json = json_encode($data);

        $path = str_replace("{paymentIntentId}", $this->getTransactionReference(), self::PATH_PAYMENT_REFUND);

        $httpResponse = $this->httpClient->post(
            $this->getEndpoint().$path,
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $this->getApiKey(),
                'version' => $this->getApiVersion(),
            ],
            $json
        )->send();
        $dataResponse = $httpResponse->json();

        //TODO remove
        /*
        $dataResponse = json_decode('{
"paymentIntentId": "pi_sandbox_RBMHTJ4fIkmSppDILZVCGw",
"refundId": "rfnd_127usj",
"refundReason": "Demo refund",
"notes": null
}', true);
        */

        return $this->response = new RefundResponse($this, $dataResponse);
    }
}
