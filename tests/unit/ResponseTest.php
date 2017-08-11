<?php

namespace AgilePay\Sdk\Tests\Unit;

use AgilePay\Sdk\Response;
use AgilePay\Sdk\Tests\Unit\TestCase;
use GuzzleHttp\Psr7\Response as PsrResponse;

class ResponseTest extends TestCase
{
    public function test__get()
    {
        $body = ['key' => true, 'nested' => [ 'key' => 'value' ]];
        $psrResponse = $this->mockPsrResponse(200, $body);
        $posResponse = new Response($psrResponse);
        $this->assertSame($posResponse->key, $body['key']);
    }

    public function testToArray()
    {
        $body = [
            'test1' => 'first_level',
            'test2' => [ 'test3' => 'nested']
        ];
        $psrResponse = $this->mockPsrResponse(200, $body);
        $posResponse = new Response($psrResponse);
        $this->assertEquals($body, $posResponse->toArray());
    }

    public function testGetStatusCode()
    {
        $posResponse = new Response($this->mockPsrResponse());
        $this->assertSame($posResponse->getStatusCode(), 200);
    }
}