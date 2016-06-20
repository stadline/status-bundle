<?php

namespace Stadline\StatusPageBundle\Requirements;

use \RequirementCollection as BaseRequirementCollection;

class RequirementCollection extends BaseRequirementCollection
{
    protected $requirements;

    /**
     * Adds a Requirement.
     *
     * @param Requirement $requirement A Requirement instance
     */
    public function add(Requirement $requirement)
    {
        $this->requirements[] = $requirement;
    }

    /**
     * Adds a mandatory requirement.
     *
     * @param bool        $fulfilled   Whether the requirement is fulfilled
     * @param string      $testMessage The message for testing the requirement
     * @param string      $helpHtml    The help text formatted in HTML for resolving the problem
     * @param string|null $helpText    The help text (when null, it will be inferred from $helpHtml, i.e. stripped from HTML tags)
     * @param bool|false  $informative If requirement is informative or not
     * @param bool|false  $dependant   If requirement depends of external element or not
     * @param bool|true   $fromApp     If requirement is from an external service or not
     */
    public function addRequirement($fulfilled, $testMessage, $helpHtml, $helpText = null, $informative = false, $dependant = false, $fromApp = true)
    {
        $this->add(new Requirement($fulfilled, $testMessage, $helpHtml, $helpText, false, $informative, $dependant, $fromApp));
    }

    /**
     * Adds an optional recommendation.
     *
     * @param bool        $fulfilled   Whether the recommendation is fulfilled
     * @param string      $testMessage The message for testing the recommendation
     * @param string      $helpHtml    The help text formatted in HTML for resolving the problem
     * @param string|null $helpText    The help text (when null, it will be inferred from $helpHtml, i.e. stripped from HTML tags)
     */
    public function addRecommendation($fulfilled, $testMessage, $helpHtml, $helpText = null)
    {
        $this->add(new Requirement($fulfilled, $testMessage, $helpHtml, $helpText, true));
    }

    /**
     * Adds a requirement collection to the current set of requirements.
     *
     * @param RequirementCollection $collection A RequirementCollection instance
     */
    public function addCollection(RequirementCollection $collection)
    {
        $this->requirements = array_merge($this->requirements, $collection->all());
    }
}