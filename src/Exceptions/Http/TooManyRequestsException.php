<?php

namespace AgilePay\Sdk\Exceptions\Http;

use DateTime;
use DateTimeZone;
use Psr\Http\Message\ResponseInterface;

class TooManyRequestsException extends \Exception
{
    /**
     * @var \DateTime
     */
    protected $resettingAt;

    public function __construct(ResponseInterface $response)
    {
        $limit = $response->getHeader('X-Ratelimit-Limit');
        $resetting = $response->getHeader('X-Ratelimit-Reset');
        $this->resettingAt = DateTime::createFromFormat('U', $resetting[0], new DateTimeZone('UTC'));
        parent::__construct("Limit of {$limit[0]} requests reached!");
    }

    /**
     * Retrieves the timestamp of the rate limit reset
     *
     * @return DateTime
     */
    public function resettingAt()
    {
        return $this->resettingAt;
    }
}