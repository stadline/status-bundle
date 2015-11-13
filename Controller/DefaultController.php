<?php

namespace Stadline\StatusPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Dumper;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $ignoreWarning = $this->getRequest()->query->get('ignore-warnings', 0);

    	$collections = $this->get('stadline_status_page.requirement.collections');
    	
        $response = $this->render('StadlineStatusPageBundle:Default:index.html.twig', array(
            'collections' => $collections,
            'title' => "Project :: status page"
        ));
        
        if ($collections->hasIssue($ignoreWarning))
        {
            $response->setStatusCode("501");
        }

        return $response;
    }
    
    public function exposeParamsAction()
    {
        if($this->getRequest()->get('full', false)) {
            $params = $this->container->getParameterBag()->all();
        } else {
            $params = $this->get("sensio_distribution.webconfigurator")->getParameters();
        }
        
        $yaml = new Dumper();
        $params = $yaml->dump($params, 5);
        
        $response = $this->render('StadlineStatusPageBundle:Default:exposeParams.html.twig', array(
            'params' => $params,
            'title' => "Project :: parameters status page"
        ));
        
        return $response;
    }
}
