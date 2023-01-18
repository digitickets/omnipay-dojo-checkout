<?php

namespace DigiTickets\Dojo\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class CompletePurchaseRequest extends AbstractDojoRequest
{
    public function getData()
    {
        return [
            'amount' => $this->getAmountInteger(),
        ];
    }

    /**
     * This doesn't call out to dojo, it just checks the webhook that comes in is valid.
     * @param $data
     *
     * @return CompletePurchaseResponse
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        $postData = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_DOJO_SIGNATURE'] ?? ''; //TODO validate with this (somehow). Check it's available in this var.
        //TODO REMOVE
        /*$postData = '{
          "id": "evt_hnnHxIKR_Uy6bhZCusCltw",
          "event": "payment_intent.status_updated",
          "accountId": "acc_test",
          "createdAt": "2022-02-01T13:07:41.8667859Z",
          "data": {
            "paymentIntentId": "pi_vpwd4ooAPEqyNAQe4z89WQ",
            "paymentStatus": "Captured",
            "captureMode": "Auto"
            }
        }';*/

        $json = json_decode($postData, true);
        if (!$json) {
            throw new InvalidResponseException("Invalid Json in body (" . json_last_error_msg(). ")");
        }
        if (empty($json["data"]["paymentIntentId"])) {
            throw new InvalidResponseException("Missing paymentIntentId");
        }
        if (($json["event"] ?? '')!='payment_intent.status_updated') {
            throw new InvalidResponseException("Only payment_intent.status_updated events should be passed through to this, received: " . ($json["event"] ?? ''));
        }

        return $this->response = new CompletePurchaseResponse($this, $json);
    }
}
