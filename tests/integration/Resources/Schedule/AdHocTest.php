<?php

namespace AgilePay\Sdk\Tests\Integration\Resources\Schedule;

use DateTime;
use AgilePay\Sdk\Resources\Schedule\AdHoc;
use AgilePay\Sdk\Tests\Integration\TestCase;

class AdHocTest extends TestCase
{
    /**
     * @var \AgilePay\Sdk\Resources\Schedule\AdHoc
     */
    protected $adHoc;

    protected function setUp()
    {
        parent::setUp();
        $this->adHoc = new AdHoc($this->client);
    }

    public function testCreate()
    {
        $response = $this->adHoc->create();
        $this->assertNotNull($response);
        $this->assertArrayHasKey('reference', $response->toArray());
    }
}