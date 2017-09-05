<?php

namespace AgilePay\Sdk\Resources;

use AgilePay\Sdk\Client;

class Credit
{
    /**
     * @var \AgilePay\Sdk\Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieves the user credit information
     *
     * @return \AgilePay\Sdk\Response
     */
    public function get()
    {
        return $this->client->get('credit');
    }

    /**
     * Top up the user credit amount
     *
     * @param integer $amount
     * @param string|array $card
     * @param array $billing
     * @return \AgilePay\Sdk\Response
     */
    public function topUp($amount, $card, array $billing = null)
    {
        return $this->client->put('credit', [
            'body' => [
                'amount' => $amount,
                'billing' => $billing,
                is_string($card) ? 'payment_method' : 'card' => $card,
            ]
        ]);
    }
}