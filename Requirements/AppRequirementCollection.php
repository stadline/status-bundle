<?php

namespace Stadline\StatusPageBundle\Requirements;

class AppRequirementCollection
{
    private $requirements = [];

    /**
     * Adds a AppRequirement.
     *
     * @param AppRequirement $requirement A Requirement instance
     */
    public function add(AppRequirement $requirement)
    {
        $this->requirements[] = $requirement;
    }

    /**
     * Adds a mandatory requirement.
     *
     * @param bool           $fulfilled   Whether the requirement is fulfilled
     * @param string         $testMessage The message for testing the requirement
     * @param string         $helpHtml    The help text formatted in HTML for resolving the problem
     * @param string|null    $helpText    The help text (when null, it will be inferred from $helpHtml, i.e. stripped from HTML tags)
     * @param array<boolean> $types       Three booleans (informative, dependant, fromApp)
     */
    public function addRequirement($fulfilled, $testMessage, $helpHtml, $helpText = null, $types = [
        AppRequirement::INFORMATIVE,
        AppRequirement::DEPENDANT,
        AppRequirement::FROM_APP
    ])
    {
        $this->add(new AppRequirement($fulfilled, $testMessage, $helpHtml, $helpText, false, $types));
    }

    /**
     * Adds an optional recommendation.
     *
     * @param bool        $fulfilled   Whether the recommendation is fulfilled
     * @param string      $testMessage The message for testing the recommendation
     * @param string      $helpHtml    The help text formatted in HTML for resolving the problem
     * @param string|null $helpText    The help text (when null, it will be inferred from $helpHtml, i.e. stripped from HTML tags)
     * @param array<boolean> $types       Three booleans (informative, dependant, fromApp)
     */
    public function addRecommendation($fulfilled, $testMessage, $helpHtml, $helpText = null, $types = [
        AppRequirement::INFORMATIVE,
        AppRequirement::DEPENDANT,
        AppRequirement::FROM_APP
    ])
    {
        $this->add(new AppRequirement($fulfilled, $testMessage, $helpHtml, $helpText, true, $types));
    }

    /**
     * Adds a requirement collection to the current set of requirements.
     *
     * @param AppRequirementCollection $collection A AppRequirementCollection instance
     */
    public function addCollection(AppRequirementCollection $collection)
    {
        $this->requirements = array_merge($this->requirements, $collection->all());
    }

    /**
     * Returns both requirements and recommendations.
     *
     * @return array Array of Requirement instances
     */
    public function all()
    {
        return $this->requirements;
    }

    /**
     * Returns all mandatory requirements.
     *
     * @return array Array of Requirement instances
     */
    public function getRequirements()
    {
        $array = array();
        foreach ($this->requirements as $req) {
            if (!$req->isOptional()) {
                $array[] = $req;
            }
        }

        return $array;
    }

    /**
     * Returns the mandatory requirements that were not met.
     *
     * @return array Array of Requirement instances
     */
    public function getFailedRequirements()
    {
        $array = array();
        foreach ($this->requirements as $req) {
            if (!$req->isFulfilled() && !$req->isOptional()) {
                $array[] = $req;
            }
        }

        return $array;
    }

    /**
     * Returns all optional recommendations.
     *
     * @return array Array of Requirement instances
     */
    public function getRecommendations()
    {
        $array = array();
        foreach ($this->requirements as $req) {
            if ($req->isOptional()) {
                $array[] = $req;
            }
        }

        return $array;
    }

    /**
     * Returns the recommendations that were not met.
     *
     * @return array Array of Requirement instances
     */
    public function getFailedRecommendations()
    {
        $array = array();
        foreach ($this->requirements as $req) {
            if (!$req->isFulfilled() && $req->isOptional()) {
                $array[] = $req;
            }
        }

        return $array;
    }
}