<?php

namespace AgilePay\Sdk\Resources;

use AgilePay\Sdk\Client;
use AgilePay\Sdk\Exceptions\ConfigurationException;

class Transaction
{
    /**
     * @var \AgilePay\Sdk\Client
     */
    protected $client;

    /**
     * The transaction reference
     *
     * @var string
     */
    protected $reference;

    /**
     * The gateway reference
     *
     * @var string
     */
    protected $gatewayReference;

    /**
     * The payment method token
     *
     * @var string
     */
    protected $paymentMethodToken;

    public function __construct(Client $client, $reference = '')
    {
        $this->client = $client;
        $this->reference = $reference;
    }

    /**
     * Retrieve the transaction details
     *
     * @return \AgilePay\Sdk\Response
     */
    public function get()
    {
        return $this->client->get("transaction/{$this->reference}");
    }

    /**
     * Retrieve the transaction list
     *
     * @param array $options
     * @return \AgilePay\Sdk\Response
     */
    public function getList(array $options = [])
    {
        return $this->client->get(
            'transactions', [
                'query' => [
                    'gateway' => $this->gatewayReference,
                    'payment_method' => $this->paymentMethodToken,
                ] + $options
            ]
        );
    }

    /**
     * Charge a credit card
     *
     * @param string $amount
     * @param string $currency
     * @param array $data
     * @return \AgilePay\Sdk\Response
     */
    public function auth($amount, $currency, array $data = [])
    {
        return $this->client->post(
            'transaction/auth', [
                'body' => [
                    'gateway' => $this->gatewayReference,
                    'payment_method' => $this->paymentMethodToken,
                    'amount' => $amount,
                    'currency_code' => $currency,
                ] + $data
            ]
        );
    }

    /**
     * Voids a previously authorized transaction
     *
     * @return \AgilePay\Sdk\Response
     */
    public function void()
    {
        return $this->client->post(
            $this->renderSecondStageUri('void')
        );
    }

    /**
     * Refund a settled transaction
     *
     * @param int $amount
     * @param string $currency
     * @return \AgilePay\Sdk\Response
     */
    public function credit($amount = null, $currency = null)
    {
        $body = [];

        if (! is_null($amount)) $body['amount'] = $amount;
        if (! is_null($currency)) $body['currency_code'] = $currency;

        return $this->client->post(
            $this->renderSecondStageUri('credit'), [
                'body' => $body
            ]
        );
    }

    /**
     * Settle a previously authorized transaction
     *
     * @param null $amount
     * @param null $currency
     * @return \AgilePay\Sdk\Response
     */
    public function capture($amount = null, $currency = null)
    {
        $body = [];

        if (! is_null($amount)) $body['amount'] = $amount;
        if (! is_null($currency)) $body['currency_code'] = $currency;

        return $this->client->post(
            $this->renderSecondStageUri('capture'), [
                'body' => $body
            ]
        );
    }

    /**
     * Set the transaction reference
     *
     * @param string $reference
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Set the gateway to use to perform the first stage transactions
     *
     * @param $reference
     * @return $this
     */
    public function setGateway($reference)
    {
        $this->gatewayReference = $reference;

        return $this;
    }

    /**
     * Set the payment method to perform the first stage transactions
     *
     * @param $token
     * @return $this
     */
    public function setPaymentMethod($token)
    {
        $this->paymentMethodToken = $token;

        return $this;
    }

    /**
     * render the uri required to process second stage transactions
     *
     * @param $transactionType
     * @return string
     * @throws ConfigurationException
     */
    protected function renderSecondStageUri($transactionType)
    {
        if ( ! $this->reference) throw new ConfigurationException(
            "The transaction {$transactionType} requires a transaction reference "
        );

        return "transaction/{$this->reference}/$transactionType";
    }

    /**
     * Render the uri required to process the first stage transactions
     *
     * @param $transactionType
     * @return string
     * @throws ConfigurationException
     */
    protected function renderFirstStageUri($transactionType)
    {
        if (   ! $this->gatewayReference
            || ! $this->paymentMethodToken
        )throw new ConfigurationException(
             "The transaction {$transactionType} requires both: "
           . "the payment method token and the gateway reference"
        );

        return sprintf("gateway/%s/payment-method/%s/%s",
            $this->gatewayReference,
            $this->paymentMethodToken,
            $transactionType
        );
    }
}