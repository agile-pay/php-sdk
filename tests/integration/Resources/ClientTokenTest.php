<?php

namespace AgilePay\Sdk\Tests\Integration\Resources;

use AgilePay\Sdk\Exceptions\Http\ValidationException;
use AgilePay\Sdk\Resources\ClientToken;
use AgilePay\Sdk\Tests\Integration\TestCase;

class ClientTokenTest extends TestCase
{
    /**
     * @var \AgilePay\Sdk\Resources\ClientToken
     */
    protected $clientToken;

    protected function setUp()
    {
        parent::setUp();
        $this->clientToken = new ClientToken($this->client);
    }

    public function testGenerate()
    {
        $response = $this->clientToken->generate('127.0.0.1');
        $this->assertArrayHasKey('token', $response->toArray());
        $this->assertArrayHasKey('valid_until', $response->toArray());
    }

}