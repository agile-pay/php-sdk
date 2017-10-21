<?php

namespace AgilePay\Sdk\Tests\Integration;

use DateTime;
use AgilePay\Sdk\Client;
use GuzzleHttp\Client as Guzzle;
use AgilePay\Sdk\Resources\Gateway;
use AgilePay\Sdk\Resources\Customer;
use AgilePay\Sdk\Resources\PaymentMethod;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AgilePay\Sdk\Client
     */
    protected $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = new Client(new Guzzle(), [
            'api_key' => getenv('API_KEY'),
            'api_secret' => getenv('API_SECRET'),
            'environment' => getenv('ENVIRONMENT')
        ]);
    }

    /**
     * Creates a new dummy gateway
     *
     * @return \AgilePay\Sdk\Response
     */
    protected function createDummyGateway()
    {
        return (new Gateway($this->client))->create('test', [
            'dummy_key' => uniqid('api-key')
        ]);
    }

    /**
     * Creates a new dummy customer
     *
     * @return \AgilePay\Sdk\Response
     */
    protected function createDummyCustomer()
    {
        return (new Customer($this->client))->create([
            'email' => 'test'.uniqid().'@email.com',
            'last_name' => 'Rossi',
            'first_name' => 'Mario',
        ]);
    }

    protected function createDummyPaymentMethod($type = 'card', array $options = [
        'keep' => false
    ])
    {
        if ($type == 'card'){
            return (new PaymentMethod($this->client))->keep($options['keep'])->createCard([
                'number' => '4007000000027',
                'holder_name' => 'Mario Rossi',
                'cvv' => '123',
                'expiry_month' => '12',
                'expiry_year' => (new DateTime())->modify('+1 year')->format('y'),
            ]);
        }
    }
}
