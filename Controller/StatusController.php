<?php

namespace Stadline\StatusPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatusController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $ignoreWarning = $request->query->get('ignore-warnings', 0);

        $collections = $this->get('stadline_status_page.requirement.collections');

        $response = $this->render('StadlineStatusPageBundle:Default:index.html.twig', array(
            'collections' => $collections,
            'title' => "Project :: status page"
        ));

        if ($collections->hasIssue($ignoreWarning)) {
            $response->setStatusCode("501");
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function exposeParamsAction(Request $request)
    {
        if ($request->get('full', false)) {
            $params = $this->container->getParameterBag()->all();
        } else {
            $params = $this->get("sensio_distribution.webconfigurator")->getParameters();
        }

        $yamlDumper = new Dumper();
        $params = $yamlDumper->dump($params, 5);

        $response = $this->render('StadlineStatusPageBundle:Default:exposeParams.html.twig', array(
            'params' => $params,
            'title' => "Project :: parameters status page"
        ));

        return $response;
    }
}
