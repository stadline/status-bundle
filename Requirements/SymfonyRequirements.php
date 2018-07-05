<?php 

namespace Stadline\StatusPageBundle\Requirements;

use Stadline\StatusPageBundle\Services\Requirement;

class SymfonyRequirements extends Requirement
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