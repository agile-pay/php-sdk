<?php

namespace AgilePay\Sdk\Tests\Unit;

use AgilePay\Sdk\AgilePay;
use AgilePay\Sdk\Resources\Credit;
use AgilePay\Sdk\Resources\Gateway;
use AgilePay\Sdk\Resources\Webhook;
use AgilePay\Sdk\Resources\Customer;
use AgilePay\Sdk\Resources\Schedule;
use AgilePay\Sdk\Resources\Transaction;
use AgilePay\Sdk\Resources\ClientToken;
use AgilePay\Sdk\Resources\PaymentMethod;
use AgilePay\Sdk\Resources\TransactionSchedule;

class AgilePayTest extends TestCase
{
    /**
     * @var AgilePay
     */
    protected $agilePay;

    protected function setUp()
    {
        parent::setUp();
        $this->agilePay = new AgilePay($this->getConfig());
    }

    public function testCredit()
    {
        $this->assertTrue($this->agilePay->credit() instanceof Credit);
    }

    public function testGateway()
    {
        $this->assertTrue($this->agilePay->gateway() instanceof Gateway);
    }

    public function testCustomer()
    {
        $this->assertTrue($this->agilePay->customer() instanceof Customer);
    }

    public function testWebhook()
    {
        $this->assertTrue($this->agilePay->webhook() instanceof Webhook);
    }

    public function testSchedule()
    {
        $this->assertTrue($this->agilePay->schedule() instanceof Schedule);
    }

    public function testTransaction()
    {
        $this->assertTrue($this->agilePay->transaction() instanceof Transaction);
    }

    public function testPaymentMethod()
    {
        $this->assertTrue($this->agilePay->paymentMethod() instanceof PaymentMethod);
    }

    public function testClientToken()
    {
        $this->assertTrue($this->agilePay->clientToken() instanceof ClientToken);
    }

    public function testTransactionSchedule()
    {
        $this->assertTrue($this->agilePay->transactionSchedule() instanceof TransactionSchedule);
    }
}