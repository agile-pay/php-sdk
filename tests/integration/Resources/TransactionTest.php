<?php

namespace AgilePay\Sdk\Tests\Integration\Resources;

use DateTime;
use AgilePay\Sdk\Resources\Gateway;
use AgilePay\Sdk\Resources\Transaction;
use AgilePay\Sdk\Resources\PaymentMethod;
use AgilePay\Sdk\Tests\Integration\TestCase;

/**
 * All the test in the class will be skipped, reason is that in order to make it work
 * a dummy actual test gateway will need to be created, and then use that gateway to
 * process all the transactions, as it stands it would only work providing real authentication
 * details from real gateways.
 *
 * Class TransactionTest
 * @package AgilePay\Sdk\Tests\Integration\Resources
 */
class TransactionTest extends TestCase
{
    /**
     * @var \AgilePay\Sdk\Resources\Transaction
     */
    protected $transaction;

    protected function setUp()
    {
        parent::setUp();
        $this->transaction = new Transaction($this->client);
        //Preparing a payment method and a gateway
        $this->transaction->setGateway($this->createDummyGateway()->reference);
        $this->transaction->setPaymentMethod($this->createDummyPaymentMethod()->token);
    }

    public function testGet()
    {
        $transactionClient = $this->transaction;
        $response = $transactionClient->auth(100, 'gbp');
        $transactionClient = new Transaction($this->client, $response->toArray()['reference']);
        $res = $transactionClient->get();
        $this->assertNotNull($res);
        $this->assertArrayHasKey('reference', $res->toArray());
    }

    public function testGetList()
    {
        $response = $this->transaction->getList();
        $this->assertEquals(200, $response->getResponse()->getStatusCode());
        //testing with options
        $trans = $this->transaction->getList(['page' => 2]);
        $this->assertEquals(2, $trans->currentPage());
    }

    public function testAuth()
    {
        $transactionClient = $this->transaction;
        $response = $transactionClient->auth(100, 'gbp');
        $this->assertNotNull($response);
        $this->assertArrayHasKey('reference', $response->toArray());
    }

    public function testVoid()
    {
        $transactionClient = $this->transaction;
        $res = $transactionClient->auth(100, 'gbp');
        $transactionClient->setReference($res->toArray()['reference']);
        $response = $transactionClient->void();
        $this->assertNotNull($response);
        $this->assertArrayHasKey('reference', $response->toArray());
    }

    public function testCredit()
    {
        $transactionClient = $this->transaction;
        $res = $transactionClient->auth(100, 'gbp');
        $transactionClient->setReference($res->toArray()['reference']);
        $transactionClient->capture(100, 'gbp');
        $response = $transactionClient->credit(100, 'gbp');
        $this->assertNotNull($response);
        $this->assertArrayHasKey('reference', $response->toArray());
    }

    public function testCapture()
    {
        $transactionClient = $this->transaction;
        $res = $transactionClient->auth(100, 'gbp');
        $transactionClient->setReference($res->toArray()['reference']);
        $response = $transactionClient->capture(100, 'gbp');
        $this->assertNotNull($response);
        $this->assertArrayHasKey('reference', $response->toArray());
    }
}