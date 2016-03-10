<?php

namespace Stadline\StatusPageBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RequirementCompilerPass implements CompilerPassInterface
{
    /**
     * Process the requirement compiler pass.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition("stadline_status_page.requirement.collections")) {
            return;
        }

        $definition = $container->getDefinition('stadline_status_page.requirement.collections');

        $taggedServices = $container->findTaggedServiceIds('status_page.requirement');

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addCollection',
                array(new Reference($id))
            );
        }
    }
}
