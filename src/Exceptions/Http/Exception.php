<?php

namespace AgilePay\Sdk\Exceptions\Http;

use Psr\Http\Message\ResponseInterface;

class Exception extends \Exception
{
    /**
     * The actual response object
     *
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(ResponseInterface $response, $message = "")
    {
        if (! strlen($message)){
            // attempting to get the error message from the response
            $response->getBody()->rewind();
            $decoded = json_decode($response->getBody()->getContents(), true);
            if (isset($decoded['error']) && is_string($decoded['error'])){
                $message = $decoded['error'];
            }
        }

        parent::__construct("HTTP error {$response->getStatusCode()} : $message");

        $this->response = $response;
    }

    public function getStatusCode()
    {
        return (int) $this->response->getStatusCode();
    }

    public function getResponse()
    {
        $this->response->getBody()->rewind();

        return $this->response;
    }
}