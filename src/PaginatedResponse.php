<?php

namespace AgilePay\Sdk;

use AgilePay\Sdk\Client;
use AgilePay\Sdk\Response;
use AgilePay\Sdk\Exceptions\PaginatedResponseException;

class PaginatedResponse
{
    /**
     * @var \AgilePay\Sdk\Client
     */
    protected $client;

    /**
     * @var \AgilePay\Sdk\Response
     */
    protected $response;

    public function __construct(Client $client, Response $response)
    {
        $this->client = $client;
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getData()
    {
        return $this->response->toArray()['data'];
    }

    public function totalItems()
    {
        return (int) $this->response->toArray()['total'];
    }

    public function currentPage()
    {
        return (int) $this->response->toArray()['current_page'];
    }

    public function isLastPage()
    {
        return $this->currentPage() == $this->totalPages();
    }

    public function totalPages()
    {
        return (int) $this->response->toArray()['last_page'];
    }

    public function nextPage()
    {

        if ($this->currentPage() < $this->totalPages()
            && array_key_exists('next_page_url', $this->response->toArray())
        ){
            $url = $this->response->toArray()['next_page_url'];
            if (strlen($url)){
                $this->response = $this->client->get($this->extrapolateUri($url));
            }
            return $this;
        }else throw PaginatedResponseException('You can\'t go forward any further');
    }

    public function previousPage()
    {
        if ($this->currentPage() > 1
            && array_key_exists('prev_page_url', $this->response->toArray())
        ){
            $url = $this->response->toArray()['prev_page_url'];
            if (strlen($url)){
                $this->response = $this->client->get($this->extrapolateUri($url));
            }
            return $this;
        }else throw new PaginatedResponseException('You can\'t go back any further');
    }

    protected function extrapolateUri($url)
    {
        $parsed = parse_url($url);

        $uri = substr($parsed['path'], 4);

        if (array_key_exists('query', $parsed)){
            $query = $parsed['query'];
        }

        return isset($query) ? "$uri?$query" : $uri;
    }

}
