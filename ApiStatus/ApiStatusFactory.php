<?php

namespace Stadline\StatusPageBundle\ApiStatus;

use Guzzle\Http\Client;

class ApiStatusFactory
{
    private static $client;

    /**
     * The factory creates an instance of ApiStatusInterface;
     *
     * @param ApiStatusInterface
     * @return ApiStatusInterface
     */
    public static function create($parameters)
    {
        if ($parameters['url'] && $parameters['name']) {
            return new PublicApiStatusPage(self::$client, $parameters['name'], $parameters['url']);
        }
    }

    /**
     * Sets the client.
     *
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        self::$client =$client;
    }
}
