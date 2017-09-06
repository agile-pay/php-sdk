<?php

namespace AgilePay\Sdk\Resources;

use AgilePay\Sdk\Client;

class TransactionSchedule
{
    /**
     * @var \AgilePay\Sdk\Client
     */
    protected $client;

    /**
     * The scheduled transaction reference
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
     * The webhook reference
     *
     * @var string
     */
    protected $webhookReference;

    /**
     * The schedule reference
     *
     * @var string
     */
    protected $scheduleReference;

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
     * Set the webhook reference for scheduled transactions
     *
     * @param $reference
     * @return $this
     */
    public function setWebhook($reference)
    {
        $this->webhookReference = $reference;

        return $this;
    }

    /**
     * The wrapper schedule to be associated to the scheduled transaction
     *
     * @param $reference
     * @return $this
     */
    public function setSchedule($reference)
    {
        $this->scheduleReference = $reference;

        return $this;
    }

    /**
     * Schedule a transaction to be executed in the future
     *
     * @param string $type The type of transaction to be scheduled
     * @param string $at The datetime when the transaction will be executed
     * @param string $timezone The timezone
     * @param array $data The transaction data
     * @param array $retries The retries array
     * @return \AgilePay\Sdk\Response
     */
    public function schedule($type, $at, $timezone, array $data, array $retries = [])
    {
        $notMandatory = [];

        if (count($retries)) $notMandatory['retries'] = $retries;
        if ($this->webhookReference) $notMandatory['webhook'] = $this->webhookReference;
        if ($this->scheduleReference) $notMandatory['schedule'] = $this->scheduleReference;

        return $this->client->post('transaction-schedules', [
            'body' => [
                'transaction_type' => $type,
                'schedule_at' => $at,
                'timezone' => $timezone,
                'gateway' => $this->gatewayReference,
                'payment_method' => $this->paymentMethodToken,
                'transaction_data' => $data
            ] + $notMandatory
        ]);
    }

    /**
     * Cancel a scheduled transaction
     *
     * @return \AgilePay\Sdk\Response
     */
    public function cancel()
    {
        return $this->client->delete("transaction-schedule/{$this->reference}");
    }
}