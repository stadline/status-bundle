<?php

namespace Stadline\StatusPageBundle\Handler;

use Stadline\StatusPageBundle\Requirements\RequirementCollections;

class StatusCodeHandler
{
    const OK_CODE = 200;
    const INTERNAL_BROKEN_CODE = 500;
    const EXTERNAL_BROKEN_CODE = 409;
    const INFORMATIVE_CODE = 417;

    /**
     * Define the most critical status code of the failed requirements collection
     *
     * @param mixed $failedRequirementsCollection
     * @param int $ignoreWarning
     * @return integer
     */
    public function defineMostCriticalStatusCode($failedRequirementsCollection, $ignoreWarning)
    {
        $statusCodeOfCollection = [];

        foreach ($failedRequirementsCollection as $collectionName => $requirements) {
            $statusCodeOfCollection[] = $this->getMostCriticalStatusCode($collectionName, $requirements);
        }

        $statusCode = max($statusCodeOfCollection); // return the bigger value

        if ($statusCode === self::INFORMATIVE_CODE && $ignoreWarning) {
            $statusCode = self::OK_CODE;
        }

        return $statusCode;
    }

    /**
     * @param RequirementCollections $collections
     * @param int $ignoreWarning
     * @return array
     */
    public function getRequirementsCollections(RequirementCollections $collections, $ignoreWarning)
    {
        $failedRequirementsCollections = $collections->getFailedRequirements();

        if (!$ignoreWarning) {
            $failedRecommendationsCollections = $collections->getFailedRecommendations();
            $failedRequirementsCollections = array_merge_recursive($failedRequirementsCollections, $failedRecommendationsCollections);
        }

        return $failedRequirementsCollections;
    }

    /**
     * Get the most critical status code of a requirement
     *
     * @param string $collectionName
     * @param array $requirements
     * @return int (500, 409 or 417)
     */
    protected function getMostCriticalStatusCode($collectionName, array $requirements)
    {
        if ($collectionName === "Symfony") {
            return self::INTERNAL_BROKEN_CODE;
        }

        $statusCode = 0;

        foreach ($requirements as $requirement) {
            $isInformative = $requirement->isInformative();
            $isDependant = $requirement->isDependant();
            $isFromApp = $requirement->isFromApp();
            $terms = [$isInformative, $isDependant, $isFromApp];

            $statusCode = $this->getStatusCode($statusCode, $terms);
        }

        return $statusCode;
    }

    /**
     * Get the status code of terms (informative, dependant and fromApp) and a "base" statusCode
     *
     * @param int $statusCode
     * @param array $terms
     * @return int
     */
    protected function getStatusCode($statusCode, array $terms)
    {
        // priority is 500, 409, 417 for status codes
        switch ($terms) {
            case array(false, false, true): // Not informative, not dependant and from app
                $statusCode = self::INTERNAL_BROKEN_CODE;
                break;
            case array(false, true, false): // Not informative, dependant and not from app
                if ($statusCode != self::INTERNAL_BROKEN_CODE) {
                    $statusCode = self::EXTERNAL_BROKEN_CODE;
                }
                break;
            default: // Informative
                if ($statusCode != self::INTERNAL_BROKEN_CODE && $statusCode != self::EXTERNAL_BROKEN_CODE) {
                    $statusCode = self::INFORMATIVE_CODE;
                }
                break;
        }

        return $statusCode;
    }
}
