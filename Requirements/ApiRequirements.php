<?php

namespace StadLine\StatusPageBundle\Requirements;

use Core\ApiBundle\Api\MandrillApiStatus;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use StadLine\StatusPageBundle\ApiStatus\ApiStatusFactory;
use StadLine\StatusPageBundle\ApiStatus\ApiStatusInterface;

class ApiRequirements extends RequirementCollection
{
    const STATUS_CODE_OK = 200;

    public function __construct($container)
    {
        $apiList = $container->getParameter('status_page.externals_api');

        foreach ($apiList as $parameters) {
            $apiStatus = ApiStatusFactory::create($parameters);

            $apiStatus->setIsAvailable($this->getAvailability($apiStatus, $container->get('guzzle.client')));
            $this->addRequirement(
                $apiStatus->isAvailable(),
                $apiStatus->getName(),
                $apiStatus->isAvailable() ? self::STATUS_CODE_OK : $apiStatus->getExceptionMessage()
            );
        }
    }

    public function getAvailability(ApiStatusInterface &$apiStatus, $client)
    {
        try {
            $request = $client->createRequest('GET',$apiStatus->getUrl());
            $response = $client->send($request);

            if ($response->getStatusCode() === self::STATUS_CODE_OK) {
                return true;
            }
        } catch (\Exception $e) {
            $apiStatus->setExceptionMessage($e->getMessage());

            return false;
        }
    }

    public function getName()
    {
        return "API";
    }
}
