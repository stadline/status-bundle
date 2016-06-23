<?php

namespace Stadline\StatusPageBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SecurityCompilerPass implements CompilerPassInterface
{
    const LOGIN_PARAM = "status_bundle.config.status_login";
    const PASS_PARAM = "status_bundle.config.status_password";
    const ROLE_STATUS_ADMIN = 'ROLE_STATUS_ADMIN';

    /**
     * Process the security compiler pass.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter(self::LOGIN_PARAM) || !$container->hasParameter(self::PASS_PARAM)) {
            return;
        }

        $login = $container->getParameter(self::LOGIN_PARAM);
        $password = $container->getParameter(self::PASS_PARAM);

        if (!empty($login) && !empty($password)) {
            $container->loadFromExtension('security', [
                'access_control' => [
                    [
                        'path' => $container->get('router')->generate('stadline_status_page_homepage'),
                        'role' => self::ROLE_STATUS_ADMIN
                    ]
                ],
                'firewalls' => [
                    'default' => [
                        'anonymous' => null,
                        'http_basic' => null,
                    ],
                ],
                'providers' => [
                    'in_memory' => [
                        'memory' => [
                            'users' => [
                                $login => [
                                    'password' => $password,
                                    'roles' => self::ROLE_STATUS_ADMIN
                                ],
                            ],
                        ],
                    ],
                ]
            ]);

            $container->compile();
        }
    }
}
