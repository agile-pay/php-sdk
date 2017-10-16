<?php

namespace AgilePay\Sdk\Resources;

use AgilePay\Sdk\Client;

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
     * @param array $fields
     * @return \AgilePay\Sdk\Response
     */
    public function update(array $fields = [])
    {
        return $this->client->put("gateways/{$this->reference}", [
            'body' => $fields
        ]);
    }

}