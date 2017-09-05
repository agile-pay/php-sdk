<?php

namespace AgilePay\Sdk;

use AgilePay\Sdk\Client;
use GuzzleHttp\Client as Guzzle;
use AgilePay\Sdk\Resources\Credit;
use AgilePay\Sdk\Resources\Gateway;
use AgilePay\Sdk\Resources\Webhook;
use AgilePay\Sdk\Resources\Schedule;
use AgilePay\Sdk\Resources\ClientToken;
use AgilePay\Sdk\Resources\Transaction;
use AgilePay\Sdk\Resources\PaymentMethod;

class AgilePay
{
    /**
     * @var \AgilePay\Sdk\Client
     */
    protected $client;

    public function __construct(array $config)
    {
        $this->client = new Client(new Guzzle(), $config);
    }

    /**
     * @return \AgilePay\Sdk\Resources\Credit
     */
    public function credit()
    {
        return new Credit($this->client);
    }

    /**
     * @param string $reference
     * @return \AgilePay\Sdk\Resources\Gateway
     */
    public function gateway($reference = '')
    {
        return new Gateway($this->client, $reference);
    }

    /**
     * @param string $reference
     * @return \AgilePay\Sdk\Resources\Schedule
     */
    public function schedule($reference = '')
    {
        return new Schedule($this->client, $reference);
    }

    /**
     * @param string $reference
     * @return Webhook
     */
    public function webhook($reference = '')
    {
        return new Webhook($this->client, $reference);
    }

    /**
     * @param string $reference
     * @return Transaction
     */
    public function transaction($reference = '')
    {
        return new Transaction($this->client, $reference);
    }

    /**
     * @return ClientToken
     */
    public function clientToken()
    {
        return new ClientToken($this->client);
    }

    /**
     * @param string $token
     * @return PaymentMethod
     */
    public function paymentMethod($token = '')
    {
        return new PaymentMethod($this->client, $token);
    }
}