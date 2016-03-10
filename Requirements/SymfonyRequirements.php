<?php 

namespace Stadline\StatusPageBundle\Requirements;

class SymfonyRequirements extends \SymfonyRequirements implements RequirementCollectionInterface
{
    /**
     * Get the name.
     *
     * @return string
     */
    public function getName()
    {
        return "Symfony";
    }
}