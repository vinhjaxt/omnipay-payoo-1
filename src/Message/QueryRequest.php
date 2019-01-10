<?php

namespace Omnipay\Payoo\Message;

use Cake\Chronos\Chronos;
use Omnipay\Common\Exception\InvalidRequestException;

class QueryRequest extends AbstractRequest
{

    public function getData()
    {
        $this->validate(
            'apiUsername',
            'apiPassword',
            'apiSignature',
            'shopId',
            'transactionId'
        );

        $secretKey = $this->getSecretKey();

        return [
            'OrderId' => $this->getTransactionId(),
            'ShopId' => $this->getShopId()
        ];
    }

    public function sendData($data)
    {
        $endpoint = $this->getBizEndpoint();

        $body     = json_encode($data);
        $response = $this->httpClient->request('POST', $endpoint . '/GetOrderInfo', [
            'APIUsername: ' . $this->getApiUsername(),
            'APIPassword: ' . $this->getApiPassword(),
            'APISignature: ' . $this->getApiSignature(),
            'Content-Type: application/json'
        ], $body)->getBody();
        $result  = json_decode($response, true);

        return $this->response = new QueryResponse($this, $result);
    }
}