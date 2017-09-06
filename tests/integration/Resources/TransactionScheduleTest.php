<?php

namespace AgilePay\Sdk\Tests\Integration\Resources;

use DateTime;
use AgilePay\Sdk\Tests\Integration\TestCase;
use AgilePay\Sdk\Resources\TransactionSchedule;

/**
 * Class TransactionScheduleTest
 * @package AgilePay\Sdk\Tests\Integration\Resources
 */
class TransactionScheduleTest extends TestCase
{
    /**
     * @var \AgilePay\Sdk\Resources\TransactionSchedule
     */
    protected $transactionSchedule;

    protected function setUp()
    {
        parent::setUp();
        $this->transactionSchedule = new TransactionSchedule($this->client);
        //Preparing a payment method and a gateway
        $this->transactionSchedule->setGateway($this->createDummyGateway()->reference);
        $this->transactionSchedule->setPaymentMethod($this->createDummyPaymentMethod()->token);
    }

    public function testCancel()
    {
        $at = (new DateTime())->modify('+1 month');
        $type = 'auth';
        $timezone = 'Europe/Rome';

        //creating a payment method to be kept in order to process a scheduled transaction
        $this->transactionSchedule->setPaymentMethod($this->createDummyPaymentMethod('card', ['keep' => true])->token);

        $response = $this->transactionSchedule->schedule($type, $at->format('Y-m-d H:i:s'), $timezone, [
            'amount' => 500,
            'currency_code' => 'eur'
        ], [
            $at->modify('+1 day')->format('Y-m-d H:i:s'),
            $at->modify('+1 day')->format('Y-m-d H:i:s'),
        ]);

        $response = $this->transactionSchedule->setReference($response->reference)->cancel();

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testScheduleAuth()
    {
        $at = (new DateTime())->modify('+1 month');
        $type = 'auth';
        $timezone = 'Europe/Rome';

        //creating a payment method to be kept in order to process a scheduled transaction
        $this->transactionSchedule->setPaymentMethod($this->createDummyPaymentMethod('card', ['keep' => true])->token);

        $response = $this->transactionSchedule->schedule($type, $at->format('Y-m-d H:i:s'), $timezone, [
            'amount' => 500,
            'currency_code' => 'eur'
        ], [
            $at->modify('+1 day')->format('Y-m-d H:i:s'),
            $at->modify('+1 day')->format('Y-m-d H:i:s'),
        ]);

        $this->assertNotNull($response);
        $this->assertArrayHasKey('reference', $response->toArray());
        $this->assertArrayHasKey('retries', $response->toArray());
    }
}