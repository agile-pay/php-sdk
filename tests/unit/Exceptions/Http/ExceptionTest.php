<?php

namespace AgilePay\Sdk\Tests\Unit\Exceptions\Http;

use AgilePay\Sdk\Exceptions\Http\Exception;
use AgilePay\Sdk\Tests\Unit\TestCase;

class ExceptionTest extends TestCase
{
    public function testGetErrors()
    {
        $body = [ 'error' => 'dummy error message' ];
        $response = $this->mockPsrResponse(400, $body);
        $exception = new Exception($response);
        $this->assertContains($body['error'], $exception->getMessage());
        $this->assertContains("400", $exception->getMessage());
        $this->assertEquals(400, $exception->getStatusCode());
        $this->assertEquals($response, $exception->getResponse());
    }
}