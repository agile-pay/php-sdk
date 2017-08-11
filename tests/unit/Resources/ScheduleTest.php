<?php

namespace AgilePay\Sdk\Tests\Unit\Resources;

use AgilePay\Sdk\Client;
use AgilePay\Sdk\Resources\Schedule;
use GuzzleHttp\Client as Guzzle;
use AgilePay\Sdk\Tests\Unit\TestCase;
use AgilePay\Sdk\Resources\Schedule\AdHoc;

class ScheduleTest extends TestCase
{
    /**
     * @var Schedule
     */
    protected $schedule;

    protected function setUp()
    {
        parent::setUp();
        $this->schedule = new Schedule(new Client(new Guzzle(), $this->getConfig()));
    }

    public function testAdHoc()
    {
        $this->assertTrue($this->schedule->adHoc() instanceof AdHoc);
    }
}