<?php

namespace AgilePay\Sdk\Exceptions\Http;

use Psr\Http\Message\ResponseInterface;

class ValidationException extends Exception
{
    /**
     * The array containing the errors
     *
     * @var array
     */
    protected $errors;

    public function __construct(ResponseInterface $response)
    {
        $decoded = json_decode($response->getBody()->getContents(), true);

        if (isset($decoded['details'])){
            $this->errors = $decoded['details'];
        }

        parent::__construct($response);
    }

    /**
     * The errors list
     *
     * @return array|mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }
}