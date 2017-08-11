<?php

namespace AgilePay\Sdk;

use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Get the relative http response status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return (int) $this->response->getStatusCode();
    }

    /**
     * Converts the response body to array
     *
     * @return array
     */
    public function toArray()
    {
        $this->response->getBody()->rewind();
        $parsed = json_decode($this->response->getBody()->getContents(), true);
        return $parsed;
    }

    /**
     * Access dynamically to the response's body properties
     *
     * @param string $field
     * @return mixed
     */
    public function __get($field)
    {
        $this->response->getBody()->rewind();
        $parsed = json_decode($this->response->getBody()->getContents());
        return $parsed->$field;
    }

}
