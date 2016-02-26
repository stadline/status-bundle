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
}
