<?php

namespace AgilePay\Sdk\Resources;

use AgilePay\Sdk\Client;
use AgilePay\Sdk\PaginatedResponse;

class Gateway
{
    /**
     * @var \AgilePay\Sdk\Client
     */
    protected $client;

    /**
     * The gateway reference
     *
     * @var string
     */
    protected $reference;

    public function __construct(Client $client, $reference = null)
    {
        $this->client = $client;
        $this->reference = $reference;
    }

    /**
     * Retrieve a specific gateway
     *
     * @return \AgilePay\Sdk\Response
     */
    public function get()
    {
        return $this->client->get('gateways/'.$this->reference);
    }

    /**
     * Retrieve the gateways list
     *
     * @param array $options
     * @return PaginatedResponse
     */
    public function getList(array $options = [])
    {
        $response = $this->client->get('gateways', [
            'query' => $options
        ]);

        return new PaginatedResponse($this->client, $response);
    }

    /**
     * Set a gateway reference
     *
     * @param $reference
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Creates a new gateway
     *
     * @param $type
     * @param array $fields
     * @return \AgilePay\Sdk\Response
     */
    public function create($type, array $fields = [])
    {
        return $this->client->post('gateways', [
            'body' => [
                'type'   => $type,
                'fields' => $fields
            ]
        ]);
    }

    /**
     * Update an existing gateway
     *
     * @param array $body
     * @return \AgilePay\Sdk\Response
     */
    public function update(array $body = [])
    {
        return $this->client->put("gateways/{$this->reference}", [
            'body' => $body
        ]);
    }

}