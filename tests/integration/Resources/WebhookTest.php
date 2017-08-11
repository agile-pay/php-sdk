<?php

namespace AgilePay\Sdk\Tests\Integration\Resources;

use AgilePay\Sdk\Resources\Webhook;
use AgilePay\Sdk\Tests\Integration\TestCase;

class WebhookTest extends TestCase
{
    /**
     * @var \AgilePay\Sdk\Resources\Webhook
     */
    protected $webhook;

    protected function setUp()
    {
        parent::setUp();
        $this->webhook = new Webhook($this->client);
    }

    public function testCreate()
    {
        $response = $this->webhook->create('https://requestb.in/'.uniqid());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotNull($response->reference);
    }

    public function testUpdate()
    {
        //Creating a webhook first
        $ref = $this->webhook->create('https://requestb.in/'.uniqid())->reference;
        $response = $this->webhook->setReference($ref)->update(['active' => 'false']);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertFalse($response->active);
    }
}