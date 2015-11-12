<?php

namespace StadLine\StatusPageBundle\Requirements;

use Core\ApiBundle\Api\MandrillApiStatus;
use StadLine\StatusPageBundle\ApiStatus\ApiStatusFactory;

class ApiRequirements extends RequirementCollection
{
    public function __construct($container)
    {
        $apiList = $container->getParameter('status_page.externals_api');

        foreach ($apiList as $parameters) {
            $apiStatus = ApiStatusFactory::create($parameters);
            $this->addRequirement($apiStatus->isAvailable(), $apiStatus->getName(), $apiStatus->isAvailable() ? '200' : 'NONE');
        }
    }

    public function getName()
    {
        return "API";
    }
}
