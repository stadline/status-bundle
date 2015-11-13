<?php 

namespace Stadline\StatusPageBundle\Requirements;

require_once '../app/SymfonyRequirements.php';

abstract class RequirementCollection extends \RequirementCollection
{
    /**
     * Returns the name of requirements collection
     */
    abstract public function getName();
}