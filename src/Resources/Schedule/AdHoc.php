<?php

namespace AgilePay\Sdk\Resources\Schedule;

use AgilePay\Sdk\Client;

class AdHoc
{
    /**
     * @var \AgilePay\Sdk\Client
     */
    protected $client;

    /**
     * The schedule reference
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
     * Creates a new schedule of type ad_hoc
     *
     * @param array $fields
     * @return \AgilePay\Sdk\Response
     */
    public function create(array $fields = [])
    {
        return $this->client->post('schedules', [
            'body' => array_merge($fields, [
                'type'   => 'ad_hoc'
            ])
        ]);
    }
}