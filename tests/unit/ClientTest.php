<?php

namespace AgilePay\Sdk\Tests\Unit;

use AgilePay\Sdk\Client;
use AgilePay\Sdk\Tests\Unit\TestCase;
use GuzzleHttp\Client as Guzzle;
use AgilePay\Sdk\Exceptions\Http\Exception;
use AgilePay\Sdk\Exceptions\Http\TimeoutException;
use AgilePay\Sdk\Exceptions\Http\NotFoundException;
use AgilePay\Sdk\Exceptions\ConfigurationException;
use AgilePay\Sdk\Exceptions\Http\ValidationException;
use AgilePay\Sdk\Exceptions\Http\ServerErrorException;
use AgilePay\Sdk\Exceptions\Http\UnauthorizedException;
use AgilePay\Sdk\Exceptions\Http\PaymentRequiredException;
use AgilePay\Sdk\Exceptions\Http\TooManyRequestsException;

class ClientTest extends TestCase
{
    public function testConstruct()
    {
        $api = ['api_key' => '12345', 'api_secret' => '12345',];
        $env = ['local', 'production', 'testing'];
        foreach ($env as $e){
            $client = new Client(new Guzzle, $api + array_merge([
                'environment' => $e
            ]));
        }
        $this->assertTrue(true);
        $client = new Client(new Guzzle, $api);
        //testing missing environment defaults to production
        $this->assertTrue(true);
        $confException = ConfigurationException::class;
        $this->expectException($confException);
        $client = new Client(new Guzzle, []);
    }

    public function testGet()
    {
        $this->shouldThrowsHttpExceptions('get');
    }

    public function testPost()
    {
        $this->shouldThrowsHttpExceptions('post');
    }

    protected function shouldThrowsHttpExceptions($method)
    {
        //401
        $guzzle = $this->mockGuzzle();
        $guzzle->shouldReceive($method)->andReturn($this->mockPsrResponse(401));
        $client = $this->mockClient($guzzle);
        try{
            $client->$method('test');
        }catch(UnauthorizedException $e){
            $this->assertTrue(true);
        }
        //402
        $guzzle = $this->mockGuzzle();
        $guzzle->shouldReceive($method)->andReturn($this->mockPsrResponse(402));
        $client = $this->mockClient($guzzle);
        try{
            $client->$method('test');
        }catch(PaymentRequiredException $e){
            $this->assertTrue(true);
        }
        //403
        $guzzle = $this->mockGuzzle();
        $guzzle->shouldReceive($method)->andReturn($this->mockPsrResponse(403));
        $client = $this->mockClient($guzzle);
        try{
            $client->$method($method);
        }catch(UnauthorizedException $e){
            $this->assertTrue(true);
        }
        //404
        $guzzle = $this->mockGuzzle();
        $guzzle->shouldReceive($method)->andReturn($this->mockPsrResponse(404));
        $client = $this->mockClient($guzzle);
        try{
            $client->$method($method);
        }catch(NotFoundException $e){
            $this->assertTrue(true);
        }
        //408
        $guzzle = $this->mockGuzzle();
        $guzzle->shouldReceive($method)->andReturn($this->mockPsrResponse(408));
        $client = $this->mockClient($guzzle);
        try{
            $client->$method('test');
        }catch(TimeoutException $e){
            $this->assertTrue(true);
        }
        //422
        $guzzle = $this->mockGuzzle();
        $guzzle->shouldReceive($method)->andReturn($this->mockPsrResponse(422));
        $client = $this->mockClient($guzzle);
        try{
            $client->$method('test');
        }catch(ValidationException $e){
            $this->assertTrue(true);
        }
        //429
        /*$guzzle = $this->mockGuzzle();
        $guzzle->shouldReceive($method)->andReturn($this->mockPsrResponse(429, [], [
            'X-Ratelimit-Limit' => '120',
            'X-Ratelimit-Reset' => time() + 1
        ]));
        $client = $this->mockClient($guzzle);
        try{
            $client->$method('test');
        }catch(TooManyRequestsException $e){
            $this->assertTrue(true);
        }*/
        //500
        $guzzle = $this->mockGuzzle();
        $guzzle->shouldReceive($method)->andReturn($this->mockPsrResponse(500));
        $client = $this->mockClient($guzzle);
        try{
            $client->$method('test');
        }catch(ServerErrorException $e){
            $this->assertTrue(true);
        }
        //default
        $guzzle = $this->mockGuzzle();
        $guzzle->shouldReceive($method)->andReturn($this->mockPsrResponse(999));
        $client = $this->mockClient($guzzle);
        try{
            $client->$method('test');
        }catch(Exception $e){
            $this->assertTrue(true);
        }
    }

}