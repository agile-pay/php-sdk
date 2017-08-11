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

    public function testCreateStripe()
    {
        $response = $this->gateway->create('stripe', [
            'secret_key' => 'dummy_public',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotNull($response->reference);
    }

    public function testCreateAuthorizeDotNet()
    {
        $response = $this->gateway->create('authorize_dot_net', [
            'api_login' => 'dummy_public',
            'transaction_key' => 'dummy_private',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotNull($response->reference);
    }

    public function testCreateBraintree()
    {
        $response = $this->gateway->create('braintree', [
            'public_key' => 'dummy_public',
            'private_key' => 'dummy_private',
            'merchant_id' => 'dummy_merchant',
            'merchant_account_id' => 'dummy_merchant_account',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotNull($response->reference);
    }

    public function testCreateCardstream()
    {
        $response = $this->gateway->create('cardstream', [
            'merchant_id' => 'dummy',
            'shared_secret' => 'dummy',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotNull($response->reference);
    }

    public function testCreateRealex()
    {
        $response = $this->gateway->create('realex', [
            'merchant_id' => 'dummy',
            'shared_secret' => 'dummy',
            'rebate_password' => 'dummy',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotNull($response->reference);
    }

    public function testCreateSecurionPay()
    {
        $response = $this->gateway->create('securion_pay', [
            'secret_key' => 'dummy',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotNull($response->reference);
    }

    public function testCreateWorldpay()
    {
        $response = $this->gateway->create('worldpay', [
            'merchant_id' => 'dummy',
            'service_key' => 'dummy',
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