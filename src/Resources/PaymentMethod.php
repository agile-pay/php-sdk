<?php

namespace AgilePay\Sdk\Resources;

use AgilePay\Sdk\Client;

class PaymentMethod
{
    /**
     * @var \AgilePay\Sdk\Client
     */
    protected $client;

    /**
     * Default options
     *
     * @var array
     */
    protected $options = [
        'keep' => false
    ];

    /**
     * The payment method token
     *
     * @var string
     */
    protected $token;

    public function __construct(Client $client, $token = '')
    {
        $this->client = $client;
        $this->token  = $token;
    }

    /**
     * Whether the payment method will be permanently stored in AgilePay
     *
     * @param bool $val
     * @return $this
     */
    public function keep($val = true)
    {
        $this->options['keep'] = $val;

        return $this;
    }

    /**
     * Retrieve the payment method details
     *
     * @return \AgilePay\Sdk\Response
     */
    public function get()
    {
        return $this->client->get("payment-methods/{$this->token}");
    }

    /**
     * Retrieve the payment methods list
     *
     * @param array $options
     * @return \AgilePay\Sdk\Response
     */
    public function getList(array $options = [])
    {
        return $this->client->get(
            'payment-methods', [
                'query' => $options
            ]
        );
    }

    /**
     * Creates a new payment method type of card
     *
     * @param array $data
     *
     * @return \AgilePay\Sdk\Response
     */
    public function createCard(array $data)
    {
        return $this->client->post('payment-methods', [
            'body' => [
                'type'    => 'card',
                'details' => $data,
                'options' => $this->options
            ]
        ]);
    }

    public function createGatewayToken($gateway, $card = [])
    {

        $details = ['gateway' => $gateway];

        if (count($card)){
            $details['card'] = $card;
        }else{
            $details['payment_method'] = $this->token;
        }

        return $this->client->post('payment-methods', [
            'body' => [
                'type'    => 'gateway_token',
                'details' => $details,
                'options' => $this->options
            ]
        ]);
    }
}