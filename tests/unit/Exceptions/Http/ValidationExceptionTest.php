<?php

namespace AgilePay\Sdk\Tests\Unit\Exceptions\Http;

use AgilePay\Sdk\Exceptions\Http\ValidationException;
use AgilePay\Sdk\Tests\Unit\TestCase;

class ValidationExceptionTest extends TestCase
{
    public function testGetErrors()
    {
        $errors = [
            'card_number' => [
                'The card number must me only digits',
                'The card number did not pass the luhn check'
            ]
        ];
        $exception = new ValidationException($this->mockPsrResponse(422, $errors));
        $this->assertEquals($exception->getErrors(), (object) $errors);
        $exception = new ValidationException($this->mockPsrResponse(422));
        $this->assertEquals($exception->getErrors(), []);
    }
}