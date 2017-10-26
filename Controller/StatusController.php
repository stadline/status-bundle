<?php

namespace Stadline\StatusPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatusController extends Controller
{
    /**
     * The index action.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $ignoreWarning = $request->query->get('ignore-warnings', 0);

        $collections = $this->get('stadline_status_page.requirement.collections');

        $response = $this->render('@StadlineStatusPage/Status/index.html.twig', array(
            'collections' => $collections,
            'title' => "Project :: status page"
        ));

        if ($collections->hasIssue($ignoreWarning)) {
            $statusCodeHandler = $this->get('stadline_status_page.statuscode.handler');
            $failedRequirementsArray = $statusCodeHandler->getRequirementsCollections($collections, $ignoreWarning);
            $statusCode = $statusCodeHandler->defineMostCriticalStatusCode($failedRequirementsArray, $ignoreWarning);

            $response->setStatusCode($statusCode);
        }

        return $response;
    }
}
