<?php 

namespace Stadline\StatusPageBundle\Requirements;

class SymfonyRequirements extends \SymfonyRequirements implements RequirementCollectionInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return "Symfony";
    }
}