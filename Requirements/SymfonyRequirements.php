<?php 

namespace StadLine\StatusPageBundle\Requirements;

require_once '../app/SymfonyRequirements.php';

class SymfonyRequirements extends \SymfonyRequirements
{
    public function getName()
    {
        return "Symfony 2";
    }
}