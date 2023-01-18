<?php

namespace DigiTickets\Dojo\Message;

use Omnipay\Common\Message\AbstractResponse;

class RegisterWebhookResponse extends AbstractResponse
{
    public function getSecrets() : array
    {
        return $this->getData()['secrets'] ?? [];
    }
    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        $webhook = $this->getData();
        return $webhook && is_array($webhook) && !empty($webhook['id']);
    }
}
