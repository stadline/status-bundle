<?php 

namespace Stadline\StatusPageBundle\Requirements;


class SymfonyRequirements extends \Symfony\Requirements\SymfonyRequirements implements RequirementCollectionInterface
{
    public function __construct($rootDir)
    {
        parent::__construct($rootDir);
    }

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