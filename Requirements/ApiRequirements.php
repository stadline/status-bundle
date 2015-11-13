<?php

namespace Stadline\StatusPageBundle\Requirements;

use Core\ApiBundle\Api\MandrillApiStatus;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Stadline\StatusPageBundle\ApiStatus\ApiStatusFactory;
use Stadline\StatusPageBundle\ApiStatus\ApiStatusInterface;

class ApiRequirements extends RequirementCollection
{
    public function __construct($container)
    {
        $apiList = $container->getParameter('status_page.externals_api');
        $factory = $container->get('stadline_status_page.api.factory');

        foreach ($apiList as $parameters) {
            $apiStatus = $factory::create($parameters);

            $this->addRequirement(
                $apiStatus->isAvailable(),
                $apiStatus->getName(),
                $apiStatus->isAvailable() ? $apiStatus::STATUS_CODE_OK : $apiStatus->getExceptionMessage()
            );
        }
    }

    public function getName()
    {
        return "API";
    }
}
