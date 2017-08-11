<?php

namespace AgilePay\Sdk\Resources;

use DateTime;
use AgilePay\Sdk\Client;

class ClientToken
{
    /**
     * @var \AgilePay\Sdk\Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Generates a new client token
     *
     * @param string $ip
     * @return \AgilePay\Sdk\Response
     */
    public function generate($ip = null)
    {
        if ( is_null($ip)){
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            elseif(isset($_SERVER['HTTP_X_FORWARDED']))
                $ip = $_SERVER['HTTP_X_FORWARDED'];
            elseif(isset($_SERVER['HTTP_FORWARDED_FOR']))
                $ip = $_SERVER['HTTP_FORWARDED_FOR'];
            elseif(isset($_SERVER['HTTP_FORWARDED']))
                $ip = $_SERVER['HTTP_FORWARDED'];
            elseif(isset($_SERVER['REMOTE_ADDR']))
                $ip = $_SERVER['REMOTE_ADDR'];
            else
                $ip = 'UNKNOWN';
        }

        return $this->client->post('client-tokens', [
            'body' => [
                'client_ip' => $ip
            ]
        ]);
    }

}