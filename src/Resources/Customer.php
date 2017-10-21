<?php

namespace AgilePay\Sdk\Resources;

use AgilePay\Sdk\Client;

class Customer
{
    /**
     * @var \AgilePay\Sdk\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $reference;

    public function __construct(Client $client, $reference = '')
    {
        $this->client = $client;
        $this->reference = $reference;
    }

    /**
     * @param $reference
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Retrieve a specific customer
     * @return \AgilePay\Sdk\Response
     */
    public function get()
    {
        return $this->client->get('customers/'.$this->reference);
    }

    /**
     * Retrieves the list of customers
     *
     * @param array $options
     * @return \AgilePay\Sdk\Response
     */
    public function getList(array $options = [])
    {

    }

    /**
     * Create a new customer
     *
     * @param array $customer
     * @return \AgilePay\Sdk\Response
     */
    public function create(array $customer)
    {
        return $this->client->post('customers', [
            'body' => $customer
        ]);
    }

    /**
     * Update an existing customer
     *
     * @param array $customer
     * @return \AgilePay\Sdk\Response
     */
    public function update(array $customer)
    {
        return $this->client->put('customers/'.$this->reference, [
            'body' => $customer
        ]);
    }

    /**
     * Attach a payment method to a customer
     *
     * @param $paymentMethod
     * @return \AgilePay\Sdk\Response
     */
    public function attachPaymentMethod($paymentMethod)
    {
        return $this->client->put(sprintf(
            'customers/%s/payment-methods/%s',
            $this->reference,
            $paymentMethod
        ));
    }

}