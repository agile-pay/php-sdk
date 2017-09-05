<?php

namespace AgilePay\Sdk\Tests\Integration\Resources;

use AgilePay\Sdk\Resources\Credit;
use AgilePay\Sdk\Tests\Integration\TestCase;

class CreditTest extends TestCase
{
    /**
     * @var \AgilePay\Sdk\Resources\Credit
     */
    protected $credit;

    protected function setUp()
    {
        parent::setUp();
        $this->credit = new Credit($this->client);
    }

    public function testGet()
    {
        $credit = $this->credit->get()->toArray();
        $this->assertArrayHasKey('amount_available', $credit);
        $this->assertArrayHasKey('amount_on_hold', $credit);
        $this->assertArrayHasKey('free_transactions', $credit);
    }

    public function testTopUp()
    {

        $credit = $this->credit->topUp(20, [
            'cvv' => '123',
            'expiry_month' => '12',
            'expiry_year' => date('y'),
            'number' => '4111111111111111',
            'holder_name' => 'Mario Rossi',
        ], [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'address1' => 'Test address',
            'address2' => '',
            'state' => 'IT',
            'city' => 'Roma',
            'zip' => '00100',
            'country_code' => 'IT',
        ])->toArray();

        $this->assertArrayHasKey('amount_available', $credit);
        $this->assertArrayHasKey('amount_on_hold', $credit);
        $this->assertArrayHasKey('free_transactions', $credit);

    }
}