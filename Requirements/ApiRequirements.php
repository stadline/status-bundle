<?php

namespace Stadline\StatusPageBundle\Requirements;

use Exception;
use Stadline\StatusPageBundle\ApiStatus\PublicApiStatusPage;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ApiRequirements extends \RequirementCollection implements RequirementCollectionInterface
{
    /**
     * @param ContainerInterface $container
     * @throws Exception
     */
    public function __construct(ContainerInterface $container)
    {
        $apiList = $container->getParameter('status_page.externals_api');

        foreach ($apiList as $parameters) {
            if ($parameters['url'] && $parameters['name']) {

                $apiStatus = new PublicApiStatusPage($parameters['name'], $parameters['url']);;

                $this->addRequirement(
                    $apiStatus->isAvailable(),
                    $apiStatus->getName(),
                    $apiStatus->isAvailable() ? PublicApiStatusPage::STATUS_CODE_OK : $apiStatus->getExceptionMessage()
                );
                continue;
            }

            throw new Exception("Invalid Externals API parameters \"url\" and \"name\"");
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "API";
    }
}
