<?php

namespace AgilePay\Sdk\Resources;

use AgilePay\Sdk\Client;

class Webhook
{
    /**
     * @var \AgilePay\Sdk\Client
     */
    protected $client;

    /**
     * The webhook reference
     *
     * @var string
     */
    protected $reference;

    public function __construct(Client $client, $reference = null)
    {
        $this->client = $client;
        $this->setReference($reference);
    }

    /**
     * Set the webhook reference
     *
     * @param $reference
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Creates a new webhook
     *
     * @param string $url
     * @param array $options
     * @return \AgilePay\Sdk\Response
     */
    public function create($url, $options = [])
    {
        return $this->client->post('webhooks', [
            'body' => [
                'url'   => $url,
            ] + $options
        ]);
    }

    /**
     * Update an existing webhook
     *
     * @param array $data
     * @return \AgilePay\Sdk\Response
     */
    public function update(array $data)
    {
        return $this->client->put("webhooks/{$this->reference}",
            [
                'body' => $data
            ]
        );
    }

    /**
     * Verifies whether the provided signature from a webhook request is valid
     *
     * @param $signature The X-Agilepay-Signature
     * @param $body The webhook request's body
     * @return bool
     */
    public function verifySignature($signature, $body)
    {
        $secret = $this->client->getConfig()['api_secret'];

        $signed = base64_encode(hash_hmac('sha256', $body, $secret));

        return hash_equals($signed, $signature);
    }
}