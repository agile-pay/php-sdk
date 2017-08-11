<?php

namespace AgilePay\Sdk\Tests\Unit;

use Mockery as m;
use AgilePay\Sdk\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\ClientInterface as GuzzleInterface;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $object
     * @return m\MockInterface|mixed
     */
    protected function mock($object)
    {
        return m::mock($object);
    }

    /**
     * Retrieves the client configuration
     *
     * @return array
     */
    protected function getConfig()
    {
        return [
            'api_key' => getenv('API_KEY'),
            'api_secret' => getenv('API_SECRET'),
            'environment' => getenv('ENVIRONMENT')
        ];
    }

    /**
     * Mock the guzzle client
     *
     * @return mixed|m\MockInterface|GuzzleInterface
     */
    protected function mockGuzzle()
    {
        return $this->mock(new Guzzle());
    }

    /**
     * Mock the client
     *
     * @param GuzzleInterface $guzzle
     * @return m\MockInterface|Client
     */
    protected function mockClient(GuzzleInterface $guzzle = null)
    {
        if (is_null($guzzle)){
            $guzzle = new Guzzle();
        }

        return $this->mock(new Client($guzzle, $this->getConfig()));
    }

    /**
     * @param int $status
     * @param array $body
     * @param array $headers
     * @return m\MockInterface|\Psr\Http\Message\ResponseInterface
     */
    protected function mockPsrResponse($status = 200, array $body = [], array $headers= [])
    {
        $headers = array_merge([
            'Content-Type' => 'application/json'
        ], $headers);

        return $this->mock(new Response($status, $headers, json_encode($body)));
    }
}
