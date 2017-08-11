<?php

namespace AgilePay\Sdk\Tests\Integration\Resources;

use DateTime;
use AgilePay\Sdk\Resources\Schedule;
use AgilePay\Sdk\Tests\Integration\TestCase;

class ScheduleTest extends TestCase
{
    /**
     * @var \AgilePay\Sdk\Resources\Schedule
     */
    protected $schedule;

    protected function setUp()
    {
        parent::setUp();
        $this->schedule = new Schedule($this->client);
    }

    public function testCancel()
    {
        $this->markTestIncomplete('TODO');
    }
}