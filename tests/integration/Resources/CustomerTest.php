<?php

namespace AgilePay\Sdk\Tests\Integration\Resources;

use AgilePay\Sdk\Resources\Customer;
use AgilePay\Sdk\Tests\Integration\TestCase;

class CustomerTest extends TestCase
{
    /**
     * @var \AgilePay\Sdk\Resources\Customer
     */
    protected $customer;

    protected function setUp()
    {
        parent::setUp();
        $this->customer = new Customer($this->client);
    }

    public function testGet()
    {
        $reference = $this->createDummyCustomer()->reference;
        $this->customer->setReference($reference);
        $customer = $this->customer->get();
        $this->assertEquals(200, $customer->getStatusCode());
        $this->assertEquals($reference, $customer->reference);
    }

    public function testGetList()
    {
        $this->markTestSkipped();
    }

    public function testCreate()
    {
        $customer = $this->createDummyCustomer();
        $this->assertEquals(200, $customer->getStatusCode());
        $this->assertArrayHasKey('reference', $customer->toArray());
    }

    public function testUpdate()
    {
        $reference = $this->createDummyCustomer()->reference;
        $this->customer->setReference($reference);
        $newEmail = 'test'.uniqid().'@email.com';
        $customer = $this->customer->update(['email' => $newEmail]);
        $this->assertEquals(200, $customer->getStatusCode());
        $this->assertEquals($reference, $customer->reference);
        $this->assertEquals($newEmail, $customer->email);
    }

    public function testAttachPaymentMethod()
    {
        $reference = $this->createDummyCustomer()->reference;
        $token = $this->createDummyPaymentMethod()->token;
        $this->customer->setReference($reference);
        $attached = $this->customer->attachPaymentMethod($token);
        $this->assertEquals(200, $attached->getStatusCode());
        $this->assertArrayHasKey('payment_methods', $attached->toArray());
        $this->assertEquals($token, $attached->toArray()['payment_methods'][0]['token']);
    }
}