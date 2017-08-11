<?php

namespace AgilePay\Sdk\Resources;

use AgilePay\Sdk\Client;
use AgilePay\Sdk\Resources\Schedule\AdHoc;

class Schedule
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

    public function adHoc()
    {
        return new AdHoc($this->client, $this->reference);
    }

    /**
     * Cancel a schedule
     * @todo
     */
    public function cancel()
    {}
}