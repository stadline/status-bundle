<?php

namespace Stadline\StatusPageBundle\DependencyInjection;

use StadLine\StatusPageBundle\StadLineStatusPageBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class StadlineStatusPageExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('status_page.externals_api', $config['externals_api']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $appRootDir = $container->getParameter('kernel.root_dir');

        if (file_exists($appRootDir . '/config/version.yml')) {
            $appLoader = new Loader\YamlFileLoader($container, new FileLocator($appRootDir . '/config'));
            $appLoader->load('version.yml');
        }
    }
}
