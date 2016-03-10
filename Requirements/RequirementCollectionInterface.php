<?php 

namespace Stadline\StatusPageBundle\Requirements;

interface RequirementCollectionInterface
{
    /**
     * Returns the name of requirements collection
     *
     * @return string
     */
    public function getName();

    /**
     * Add a requirement.
     *
     * @param $fulfilled
     * @param $testMessage
     * @param $helpHtml
     * @param null $helpText
     */
    public function addRequirement($fulfilled, $testMessage, $helpHtml, $helpText = null);

    /**
     * Returns all mandatory requirements.
     *
     * @return array Array of Requirement instances
     */
    public function getRequirements();

    /**
     * Returns the mandatory requirements that were not met.
     *
     * @return array Array of Requirement instances
     */
    public function getFailedRequirements();

    /**
     * Returns all optional recommendations.
     *
     * @return array Array of Requirement instances
     */
    public function getRecommendations();

    /**
     * Returns the recommendations that were not met.
     *
     * @return array Array of Requirement instances
     */
    public function getFailedRecommendations();

    /**
     * Adds an optional recommendation.
     *
     * @param bool        $fulfilled   Whether the recommendation is fulfilled
     * @param string      $testMessage The message for testing the recommendation
     * @param string      $helpHtml    The help text formatted in HTML for resolving the problem
     * @param string|null $helpText    The help text (when null, it will be inferred from $helpHtml, i.e. stripped from HTML tags)
     */
    public function addRecommendation($fulfilled, $testMessage, $helpHtml, $helpText = null);
}
