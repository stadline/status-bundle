<?php 

namespace Stadline\StatusPageBundle\Requirements;

use Symfony\Requirements\Requirement;

class SymfonyRequirements extends Requirement implements RequirementCollectionInterface
{
    /**
     * Get the name.
     *
     * @return string
     */
    public function getName()
    {
        return "Symfony";
    }

    /**
     * Add a requirement.
     *
     * @param $fulfilled
     * @param $testMessage
     * @param $helpHtml
     * @param null $helpText
     */
    public function addRequirement($fulfilled, $testMessage, $helpHtml, $helpText = null)
    {
        // TODO: Implement addRequirement() method.
    }

    /**
     * Returns all mandatory requirements.
     *
     * @return array Array of Requirement instances
     */
    public function getRequirements()
    {
        // TODO: Implement getRequirements() method.
    }

    /**
     * Returns the mandatory requirements that were not met.
     *
     * @return array Array of Requirement instances
     */
    public function getFailedRequirements()
    {
        // TODO: Implement getFailedRequirements() method.
    }

    /**
     * Returns all optional recommendations.
     *
     * @return array Array of Requirement instances
     */
    public function getRecommendations()
    {
        // TODO: Implement getRecommendations() method.
    }

    /**
     * Returns the recommendations that were not met.
     *
     * @return array Array of Requirement instances
     */
    public function getFailedRecommendations()
    {
        // TODO: Implement getFailedRecommendations() method.
    }

    /**
     * Adds an optional recommendation.
     *
     * @param bool $fulfilled Whether the recommendation is fulfilled
     * @param string $testMessage The message for testing the recommendation
     * @param string $helpHtml The help text formatted in HTML for resolving the problem
     * @param string|null $helpText The help text (when null, it will be inferred from $helpHtml, i.e. stripped from HTML tags)
     */
    public function addRecommendation($fulfilled, $testMessage, $helpHtml, $helpText = null)
    {
        // TODO: Implement addRecommendation() method.
    }
}