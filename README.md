StadlineStatusPageBundle
==============================

Compatible uniquement Symfony2 (testé Symfony 2.8.x) et Symfony3 (testé Symfony 3.0.x et 3.1.x)

Installation:
-------------

app/AppKernel.php

    new Stadline\StatusPageBundle\StadlineStatusPageBundle()

app/config/routing.yml

    stadline_status_page:
        resource: "@StadlineStatusPageBundle/Resources/config/routing.yml"
        prefix:   /

composer.json

    "scripts": {
        "post-install-cmd": [
            "Stadline\\StatusPageBundle\\Composer\\ScriptHandler::buildVersion"
        ],
        "post-update-cmd": [
            "Stadline\\StatusPageBundle\\Composer\\ScriptHandler::buildVersion"
        ]
    }

    "require": {
        "stadline/status-bundle": "dev-master"
    }

    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/stadline/status-bundle.git"
        }
    ]

    # Symfony 2
    "autoload": {
        "classmap": [
            "app/SymfonyRequirements.php"
        ]
    }

    # Symfony 3
    "autoload": {
        "classmap": [
            "var/SymfonyRequirements.php"
        ]
    }

.gitignore (à la racine du projet)

    # version file
    /app/config/version.yml
    
security.yml

    security:
        encoders:
            [...]
            Symfony\Component\Security\Core\User\User: plaintext
        
        providers:
            [...]
            status_page:
                memory:
                    users:
                        '%status_page.credentials.username%': { password: '%status_page.credentials.password%' }
                    
        firewalls:
            [...]
            # enable password authentication for status page
            status:
                pattern: ^/status
                http_basic:
                    provider: status_page

parameters.yml

    [...]
    status_page.credentials.username: 'login'
    status_page.credentials.password: 'pass'
    
parameters.yml.dist

    [...]
    # Status page security
    status_page.credentials.username: 'login'
    status_page.credentials.password: 'pass'


A propos
--------

Ce Bundle permet d'ajouter des prérequis à la plateforme qui seront testés et affichés sur la page de status de l'application

Ajouter des prérequis
---------------------

Depuis votre bundle, créez une classe de prérequis

    <?php

    // src/My/CustomBundle/Requirements/CustomRequirements.php

    namespace MyCustomBundle\Requirements;

    use Stadline\StatusPageBundle\Requirements\AppRequirementCollection;
    use Stadline\StatusPageBundle\Requirements\RequirementCollectionInterface;

    class CustomRequirements extends AppRequirementCollection implements RequirementCollectionInterface
    {
        public function __construct()
        {
            $this->addRequirement(0 == 1, "False requirement failed", "<pre>try to put 0 == 0</pre>");
            $this->addRecommendation(1 == 1, "True recommendation succeed", "It's OK");

            // Possibilité d'ajouter des options sur les requirements et les recommandations :
            // informative, dependant, fromApp via une matrice ($types) de 3 booléens
            // [false, false, true] par défaut
            $this->addRequirement(0 == 1, "False requirement failed", "<pre>try to put 0 == 0</pre>", $types = [
                $informative = false,
                $dependant = false,
                $fromApp = true
            ]);
        }

        public function getName()
        {
            return "Custom";
        }
    }

Puis ajoutez cette classe en tant que service de prérequis

    # src/My/CustomBundle/Resources/services.yml
    parameters:
        my_custom.requirement.class: My\CustomBundle\Requirements\CustomRequirements

    services:
        my_custom.requirement:
            class: %my_custom.requirement.class%
            tags:
              - { name: status_page.requirement}

Le service taggé *status_page.requirement* sera automatiquement ajouté à la page de status disponible à l'url /status

Les prérequis
-------------

Il existe 2 niveaux de prérequis :

-   Les prérequis stricts
-   Les recommendations

Les recommendations sont des prérequis optionnels.

La page de status
-----------------

La page est disponible à l'url /status ainsi que /status?ignore-warnings=1. Celle ci répertorie tous les prérequis et recommendations enregistrées.

Le code status de la page fluctue en fonction des erreurs rencontrées dans les prérequis et de la prise en compte ou non des recommendations :

**/status**

-   200 : Aucune erreur
-   409 : Service externe non OK
-   417 : Point informatif non OK
-   500 : Des recommendations "vitales" en erreur
-   500 : Des prérequis "vitaux" en erreur

**/status?ignore-warnings=1**

-   200 : Aucune erreur
-   200 : Des recommendations en erreur
-   200 : Un point informatif en erreur
-   409 : Service externe non OK
-   500 : Des prérequis "vitaux" en erreur
