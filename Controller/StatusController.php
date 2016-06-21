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

        $response = $this->render('StadlineStatusPageBundle:Status:index.html.twig', array(
            'collections' => $collections,
            'title' => "Project :: status page"
        ));

        if ($collections->hasIssue($ignoreWarning)) {
            $failedRequirementsCollections = $collections->getFailedRequirements();

            if (!$ignoreWarning) {
                $failedRecommendationsCollections = $collections->getFailedRecommendations();
                $failedRequirementsCollections = array_merge_recursive($failedRequirementsCollections, $failedRecommendationsCollections);
            }

            $statusCodeHandler = $this->get('stadline_status_page.statuscode.handler');

            $response->setStatusCode($statusCodeHandler->defineMostCriticalStatusCode($failedRequirementsCollections));
        }

        return $response;
    }
}
