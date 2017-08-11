<?php

namespace AgilePay\Sdk\Tests\Integration\Resources;

use DateTime;
use AgilePay\Sdk\Resources\PaymentMethod;
use AgilePay\Sdk\Tests\Integration\TestCase;

class PaymentMethodTest extends TestCase
{
    /**
     * @var \AgilePay\Sdk\Resources\PaymentMethod
     */
    protected $paymentMethod;

    protected function setUp()
    {
        parent::setUp();
        $this->paymentMethod = new PaymentMethod($this->client);
    }

    public function testGet()
    {
        $response = $this->paymentMethod->createCard([
            'number' => '4111111111111111',
            'holder_name' => 'Mario Rossi',
            'cvv' => '123',
            'expiry_month' => '12',
            'expiry_year' => (new DateTime())->modify('+1 year')->format('y'),
        ]);

        $token = $response->toArray()['token'];

        $res = (new PaymentMethod($this->client, $token))->get();

        $this->assertNotNull($res);
        $this->assertEquals($token, $res->toArray()['token']);
    }

    public function testGetList()
    {
        $res = $this->paymentMethod->getList();
        $this->assertNotNull($res);
    }

    public function testCreateCard()
    {
        $response = $this->paymentMethod->createCard([
            'number' => '4111111111111111',
            'holder_name' => 'Mario Rossi',
            'cvv' => '123',
            'expiry_month' => '12',
            'expiry_year' => (new DateTime())->modify('+1 year')->format('y'),
        ]);
        $this->assertNotNull($response);
        $this->assertArrayHasKey('token', $response->toArray());
    }

    public function testCreateGatewayToken()
    {
        $card = $this->createDummyPaymentMethod('card')->token;
        $gateway = $this->createDummyGateway()->reference;

        $paymentMethod = new PaymentMethod($this->client, $card);

        $response = $paymentMethod->createGatewayToken($gateway);
        $this->assertNotNull($response);
        $this->assertArrayHasKey('token', $response->toArray());

        //let's test by providing specific card details too
        $paymentMethod = new PaymentMethod($this->client);
        $response = $paymentMethod->createGatewayToken($gateway, [
            'number' => '4007000000027',
            'holder_name' => 'Mario Rossi',
            'cvv' => '123',
            'expiry_month' => '12',
            'expiry_year' => (new \DateTime())->modify('+1 year')->format('y')
        ]);
        $this->assertNotNull($response);
        $this->assertArrayHasKey('token', $response->toArray());

    }
}