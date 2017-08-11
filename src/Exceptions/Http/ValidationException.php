<?php

namespace AgilePay\Sdk\Exceptions\Http;

use Exception;
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
        $this->errors = json_decode(
            $response->getBody()->getContents()
        );
        parent::__construct('Malformed request!');
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