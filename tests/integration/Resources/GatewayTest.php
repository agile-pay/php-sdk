<?php

namespace AgilePay\Sdk\Tests\Integration\Resources;

use AgilePay\Sdk\Resources\Gateway;
use AgilePay\Sdk\Tests\Integration\TestCase;

class GatewayTest extends TestCase
{
    /**
     * @var \AgilePay\Sdk\Resources\Gateway
     */
    protected $gateway;

    protected function setUp()
    {
        parent::setUp();
        $this->gateway = new Gateway($this->client);
    }

    public function testGet()
    {
        $gateway = $this->createDummyGateway();
        $this->gateway->setReference($gateway->reference);
        $response = $this->gateway->get();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($gateway->reference, $response->reference);
    }

    public function testGetList()
    {
        $gateway = $this->createDummyGateway();
        $response = $this->gateway->getList();
        $this->assertEquals(200, $response->getResponse()->getStatusCode());
        //testing with options
        $gateways = $this->gateway->getList(['page' => 2]);
        $this->assertEquals(2, $gateways->currentPage());
    }

    public function testCreate()
    {
        $response = $this->gateway->create('stripe', [
            'secret_key' => 'dummy_public',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotNull($response->reference);
    }


    public function testUpdate()
    {
        $response = $this->gateway->create('test', [
            'dummy_key' => 'dummy',
        ]);

        $updated = (new Gateway($this->client, $response->reference))->update([
            'dummy_key' => 'dummy_updated'
        ]);

        $this->assertEquals(200, $updated->getStatusCode());
    }
}