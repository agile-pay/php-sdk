<?php

namespace AgilePay\Sdk;

use DateTime;
use DateTimeZone;
use AgilePay\Sdk\Response;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use AgilePay\Sdk\Exceptions\Http\Exception;
use AgilePay\Sdk\Exceptions\Http\TimeoutException;
use AgilePay\Sdk\Exceptions\Http\NotFoundException;
use AgilePay\Sdk\Exceptions\ConfigurationException;
use AgilePay\Sdk\Exceptions\Http\ValidationException;
use AgilePay\Sdk\Exceptions\Http\ServerErrorException;
use AgilePay\Sdk\Exceptions\Http\UnauthorizedException;
use AgilePay\Sdk\Exceptions\Http\PaymentRequiredException;
use AgilePay\Sdk\Exceptions\Http\TooManyRequestsException;

class Client
{
    const API_VERSION = 1;

    const ENV_LOCAL = 'local';
    const ENV_TESTING = 'testing';
    const ENV_PRODUCTION = 'production';

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $http;

    /**
     * The configuration
     *
     * @var array
     */
    protected $config;

    protected $baseUris = [
        self::ENV_LOCAL      => 'https://api.agilepay.dev/v'.self::API_VERSION.'/',
        self::ENV_TESTING    => 'https://api.agilepay.dev/v'.self::API_VERSION.'/',
        self::ENV_PRODUCTION => 'https://api.agilepay.io/v'.self::API_VERSION.'/',
    ];

    public function __construct(ClientInterface $guzzle, array $config)
    {
        //If the environment is missing let's default it to production
        if (! array_key_exists('environment', $config)){
            $config['environment'] = self::ENV_PRODUCTION;
        }

        $this->setConfig($config);
        $this->http = $guzzle;
    }

    /**
     * Set the configuration
     *
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $config = array_merge([
            'max_fail_retries' => 3
        ], $config);

        $this->validateConfig($config);

        $this->config = $config;
    }

    /**
     * Get the configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $uri
     * @param array $options
     * @return \AgilePay\Sdk\Response
     */
    public function get($uri, array $options = [])
    {
        return $this->request('GET', $uri, $options);
    }

    /**
     * @param $uri
     * @param array $options
     * @return \AgilePay\Sdk\Response
     */
    public function put($uri, array $options = [])
    {
        return $this->request('PUT', $uri, $options);
    }

    /**
     * @param $uri
     * @param array $options
     * @return \AgilePay\Sdk\Response
     */
    public function post($uri, array $options = [])
    {
        return $this->request('POST', $uri, $options);
    }

    /**
     * Perform an http request
     *
     * @param $method
     * @param $uri
     * @param array $options
     */
    protected function request($method, $uri, array $options = [])
    {
        if (array_key_exists('body', $options)){
            //At the moment the service only accepts json
            $options['body'] = json_encode($options['body']);
            $options['headers'] =  [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ];
        }else{
            $options['body'] = '';
        }

        $options = array_merge_recursive(
            $options, [
                'base_uri' => $this->getBaseUri(),
                'http_errors' => false,
                'verify' => $this->config['environment'] == self::ENV_PRODUCTION ? true : false
            ]
        );

        $attempts = 0;

        do {
            try {
                //if the request contains a query string we need to append it to the URI so that
                //the signature will be calculated based on the query string variables as well
                if (array_key_exists('query', $options) && strlen(implode($options['query']))){
                    $uri = "$uri?".http_build_query($options['query']);
                }

                //setting Authorization
                $options['headers']['Authorization'] = "AP {$this->config['api_key']}:{$this->sign($method, $uri, $options['body'])}";
                $response = $this->parseResponse($this->http->{strtolower($method)}($uri, $options));
                //exit from loop
                break;
            }catch (TooManyRequestsException $e){
                //sleep until is able to process further requests
                $now = new DateTime('now', new DateTimeZone('UTC'));
                sleep((int) $e->resettingAt()->getTimestamp() - $now->getTimestamp());
            }

        } while(++$attempts <= $this->config['max_fail_retries']);

        return $response;
    }

    /**
     * Sign the request
     *
     * @param string $method
     * @param string $uri
     * @param string $body
     * @return string
     */
    private function sign($method, $uri, $body = '')
    {
        return hash_hmac('sha256', base64_encode(
            $method
            . $this->getBaseUri().$uri
            . utf8_encode($body)
            . time()
        ), $this->config['api_secret']);
    }

    /**
     * Get the base uri
     *
     * @return string
     */
    private function getBaseUri()
    {
        return $this->baseUris[$this->config['environment']];
    }

    /**
     * Parse the http response
     *
     * @param ResponseInterface $response
     * @return \AgilePay\Sdk\Response
     * @throws ServerErrorException
     * @throws TimeoutException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws ValidationException
     */
    private function parseResponse(ResponseInterface $response)
    {
        $status = $response->getStatusCode();
        if (in_array($status, [200, 201, 204])){
            return new Response($response);
        }else switch ($status){
            //4xx
            case 401 : throw new UnauthorizedException('Wrong authentication'); break;
            case 402 : throw new PaymentRequiredException('Either a payment is required or you don\'t have enough credit'); break;
            case 403 : throw new UnauthorizedException('You are not authorized to perform this action'); break;
            case 404 : throw new NotFoundException($response->getBody()->getContents()); break;
            case 408 : throw new TimeoutException(); break;
            case 422 : throw new ValidationException($response); break;
            case 429 : throw new TooManyRequestsException($response); break;
            case 500 : throw new ServerErrorException("An internal error has occurred, sorry for the inconvenience."); break;
            default  : throw new Exception("An error has occurred, sorry for the inconvenience. HTTP Code : $status");
        }
    }

    private function validateConfig(array $config)
    {
        $required = ['api_key', 'api_secret', 'environment'];
        foreach ($required as $field){
            if (   ! array_key_exists($field, $config)
                || ! strlen($config[$field])
            )throw new ConfigurationException(
                'Missing required configuration field : '.$field
            );
        }

        if (! in_array($config['environment'], [
            self::ENV_LOCAL,
            self::ENV_TESTING,
            self::ENV_PRODUCTION
        ]))throw new ConfigurationException(
            'Invalid environment : '.$config['environment']
        );

        if (! is_integer($config['max_fail_retries']) || $config['max_fail_retries'] < 0){
            throw new ConfigurationException(
                'Invalid max_fail_retries, it must be positive integer '
            );
        }

    }

}