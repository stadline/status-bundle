<?php

namespace Stadline\StatusPageBundle\Requirements;

class AppRequirement extends \Requirement
{
    protected $informative;
    protected $dependant;
    protected $fromApp;

    /**
     * Constructor that initializes the requirement.
     *
     * @param bool        $fulfilled   Whether the requirement is fulfilled
     * @param string      $testMessage The message for testing the requirement
     * @param string      $helpHtml    The help text formatted in HTML for resolving the problem
     * @param string|null $helpText    The help text (when null, it will be inferred from $helpHtml, i.e. stripped from HTML tags)
     * @param bool        $optional    Whether this is only an optional recommendation not a mandatory requirement
     * @param bool        $informative If requirement is informative or not
     * @param bool        $dependant   If requirement depends of external element or not
     * @param bool        $fromApp     If requirement is from an external service or not
     */
    public function __construct($fulfilled, $testMessage, $helpHtml, $helpText = null, $optional = false, $informative = false, $dependant = false, $fromApp = true)
    {
        parent::__construct($fulfilled, $testMessage, $helpHtml, $helpText, $optional);

        $this->informative = (bool) $informative;
        $this->dependant = (bool) $dependant;
        $this->fromApp = (bool) $fromApp;
    }

    /**
     * @return bool true if informative, false if not
     */
    public function isInformative()
    {
        return $this->informative;
    }

    /**
     * @return bool true if dependant from an element, false if not
     */
    public function isDependant()
    {
        return $this->dependant;
    }

    /**
     * @return bool true if is from app, false if external
     */
    public function isFromApp()
    {
        return $this->fromApp;
    }
}