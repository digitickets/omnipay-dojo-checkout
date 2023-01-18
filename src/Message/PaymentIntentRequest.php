<?php

namespace DigiTickets\Dojo\Message;

use DateInterval;
use DateTime;

class PaymentIntentRequest extends AbstractDojoRequest
{
    /**
     * @return array
     *
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('amount');

        $card = $this->getCard();

        $data = [];
        $data['amount']['value'] = $this->getAmountInteger();
        $data['amount']['currencyCode'] = $this->getCurrency();
        $data['reference'] = $this->getTransactionId();
        $data['description'] = $this->getDescription();
        $data['customer']['emailAddress'] = $card->getEmail();
        $data['customer']['phoneNumber'] = $card->getBillingPhone();
        $data['billingAddress']['address1'] = $card->getBillingAddress1();
        $data['billingAddress']['address2'] = $card->getBillingAddress2();
        $data['billingAddress']['city'] = $card->getBillingCity();
        $data['billingAddress']['state'] = $card->getBillingState();
        $data['billingAddress']['postcode'] = $card->getBillingPostcode();
        $data['billingAddress']['countryCode'] = $card->getBillingCountry();
        $data['config']['redirectUrl'] = $this->getReturnUrl();
        $data['config']['cancelUrl'] = $this->getCancelUrl();
        $data['requestSecurity']['userAgent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $data['requestSecurity']['ipAddress'] = $this->getClientIp();

        return $data;
    }

    /**
     * @param mixed $data The data to send
     *
     * @return PaymentIntentResponse
     */
    public function sendData($data)
    {
        $json = json_encode($data);

        $httpResponse = $this->httpClient->post(
            $this->getEndpoint().self::PATH_PAYMENT_INTENT,
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $this->getApiKey(),
                'version' => $this->getApiVersion(),
            ],
            $json
        )->send();
        $responseData = $httpResponse->json();
        //TODO REMOVE
        /*
                $data = json_decode('{
        "id": "pi_sandbox_RBMHTJ4fIkmSppDILZVCGw",
        "captureMode": "Auto",
        "clientSessionSecret": "lmoFsbTJxoiOBgeWkEoFS05ADIQ6STJfJE3oGVNO2cFSb9kza06wGan2DVNceOYCsmZ5I1SiEioFausOAkecUPz8BKSmMV96ecXZZ4-NzoiYZJF0uVYeW8xosG6blQKtZ6HqIIF8--2a7DC_iQ==",
        "clientSessionSecretExpirationDate": "2022-02-21T15:09:21Z",
        "status": "Created",
        "paymentMethods": [
        "Card"
        ],
        "amount": {
        "value": 1000,
        "currencyCode": "GBP"
        },
        "totalAmount": {
        "value": 1000,
        "currencyCode": "GBP"
        },
        "createdAt": "2022-02-21T14:39:21.6050276Z",
        "updatedAt": "2022-02-21T14:39:21.6050277Z",
        "reference": "Order 234",
        "description": "Demo payment intent"
        }', true);*/

        return $this->response = new PaymentIntentResponse($this, $responseData);
    }
}
