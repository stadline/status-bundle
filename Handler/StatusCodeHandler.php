<?php

namespace Stadline\StatusPageBundle\Handler;

class StatusCodeHandler
{
    /**
     * Define the most critical status code of the failed requirements collection
     *
     * @param mixed $failedRequirementsCollection
     * @return integer
     */
    public function defineMostCriticalStatusCode($failedRequirementsCollection)
    {
        $statusCodeOfCollection = [];

        foreach ($failedRequirementsCollection as $collectionName => $requirements) {
            $statusCodeOfCollection[] = $this->getMostCriticalStatusCode($collectionName, $requirements);
        }

        return max($statusCodeOfCollection); // return the bigger value
    }

    /**
     * Get the most critical status code of a requirement
     *
     * @param string $collectionName
     * @param array $requirements
     * @return int (500, 409 or 417)
     */
    protected function getMostCriticalStatusCode($collectionName, $requirements)
    {
        if ($collectionName === "Symfony") {
            return 500;
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
    protected function getStatusCode($statusCode, $terms)
    {
        // priority is 500, 409, 417 for status codes
        switch ($terms) {
            case array(false, false, true): // Not informative, not dependant and from app
                $statusCode = 500;
                break;
            case array(false, true, false): // Not informative, dependant and not from app
                if ($statusCode != 500) {
                    $statusCode = 409;
                }
                break;
            default: // Informative
                if ($statusCode != 500 && $statusCode != 409) {
                    $statusCode = 417;
                }
                break;
        }

        return $statusCode;
    }
}
