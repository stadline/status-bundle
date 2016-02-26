<?php

namespace Stadline\StatusPageBundle\Requirements;

use Symfony\Component\DependencyInjection\ContainerInterface;

class VersionRequirements extends \RequirementCollection implements RequirementCollectionInterface
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        if ($container->hasParameter('build_commit_tag')) {
            $tag = $container->getParameter('build_commit_tag');
        }

        if ($container->hasParameter('build_commit_hash')) {
            $hash = $container->getParameter('build_commit_hash');
        }

        if ($container->hasParameter('build_commit_branch')) {
            $branch = $container->getParameter('build_commit_branch');
        }

        $this->addRequirement(isset($tag), "Git commit tag", isset($tag) ? $tag : 'NONE');
        $this->addRequirement(isset($hash), "Git commit hash", isset($hash) ? $hash : 'NONE');
        $this->addRequirement(isset($branch), "Git Branch", isset($branch) ? $branch : 'NONE');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "Version";
    }
}
