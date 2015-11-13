<?php

namespace Stadline\StatusPageBundle\ApiStatus;

use Guzzle\Http\Client;

class PublicApiStatusPage implements ApiStatusInterface
{
    const STATUS_CODE_OK = 200;

    /** @var string */
    private $url;

    /** @var string */
    private $name;

    /** @var string */
    private $exceptionMessage;

    public function __construct(Client $client, $name, $url)
    {
        $this->client = $client;
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * is api status page available.
     *
     * @return boolean
     */
    public function IsAvailable()
    {
        try {
            $request = $this->client->createRequest('GET', $this->getUrl());
            $response = $this->client->send($request);

            if ($response->getStatusCode() === self::STATUS_CODE_OK) {
                return true;
            }
        } catch (\Exception $e) {
            $this->exceptionMessage = $e->getMessage();

            return false;
        }
    }

    /**
     * Gets the url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get the public api status page name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the exception message.
     *
     * @return string
     */
    public function getExceptionMessage()
    {
        return $this->exceptionMessage;
    }
}
