<?php

namespace AgilePay\Sdk\Tests\Unit\Exceptions\Http;

use DateTime;
use AgilePay\Sdk\Tests\Unit\TestCase;
use AgilePay\Sdk\Exceptions\Http\TooManyRequestsException;

class TooManyRequestsExceptionTest extends TestCase
{
    public function testGetErrors()
    {
        $datetime = new DateTime();
        $datetime->modify('+1 minutes');
        $exception = new TooManyRequestsException(
            $this->mockPsrResponse(429, [], [
                'X-Ratelimit-Limit' => '120',
                'X-Ratelimit-Reset' => $datetime->getTimestamp()
            ])
        );
        $this->assertRegExp('/120/', $exception->getMessage());
        $this->assertEquals(
            $datetime->getTimestamp(),
            $exception->resettingAt()->getTimestamp()
        );
    }
}