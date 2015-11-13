<?php

namespace Stadline\StatusPageBundle;

use Stadline\StatusPageBundle\DependencyInjection\Compiler\RequirementCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StadlineStatusPageBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
		
		$container->addCompilerPass(new RequirementCompilerPass());
	}
}
