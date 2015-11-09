StadLineStatusPageBundle
==============================

Installation:
------------

app/AppKernel.php

    new StadLine\StatusPageBundle\StadLineStatusPageBundle()

app/config/routing.yml

    stadline_status_page:
        resource: "@StadLineStatusPageBundle/Resources/config/routing.yml"
        prefix:   /

app/config/assetic.yml

    assetic:
        bundles:
            - StadLineStatusPageBundle


A propos
--------

Ce Bundle permet d'ajouter des prérequis à la plateforme qui seront testés et affichés sur la page de status de l'application

Ajouter des prérequis
---------------------

Depuis votre bundle, créez une classe de prérequis

    <?php
    
    // src/My/CustomBundle/Requirements/CustomRequirements.php
    
    namespace MyCustomBundle\Requirements;
    
    use StadLine\StatusPageBundle\Requirements\RequirementCollection;
    
    class CustomRequirements extends RequirementCollection
    {
        public function __construct()
        {
            $this->addRequirement(0 == 1, "False requirement failed", "<pre>try to put 0 == 0</pre>");
            $this->addRecommendation(1 == 1, "True recommendation succeed", "It's OK");
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
-   501 : Des recommendations en erreur
-   501 : Des prérequis en erreur 

**/status?ignore-warnings=1**

-   200 : Aucune erreur
-   200 : Des recommendations en erreur
-   501 : Des prérequis en erreur 
