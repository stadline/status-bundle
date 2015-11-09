<?php

namespace StadLine\StatusPageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use StadLine\StatusPageBundle\DependencyInjection\Compiler\RequirementCompilerPass;

class StadLineStatusPageBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
		
		$container->addCompilerPass(new RequirementCompilerPass());
	}
}
