<?php

namespace Stadline\StatusPageBundle\Requirements;

use \Requirement as BaseRequirement;

class Requirement extends BaseRequirement
{
    protected $fulfilled;
    protected $testMessage;
    protected $helpText;
    protected $helpHtml;
    protected $optional;
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
        $this->fulfilled = (bool) $fulfilled;
        $this->testMessage = (string) $testMessage;
        $this->helpHtml = (string) $helpHtml;
        $this->helpText = null === $helpText ? strip_tags($this->helpHtml) : (string) $helpText;
        $this->optional = (bool) $optional;
        $this->informative = (bool) $informative;
        $this->dependant = (bool) $dependant;
        $this->fromApp = (bool) $fromApp;
    }

    /**
     * @return boolean
     */
    public function isInformative()
    {
        return $this->informative;
    }

    /**
     * @return boolean
     */
    public function isDependant()
    {
        return $this->dependant;
    }

    /**
     * @return boolean
     */
    public function isFromApp()
    {
        return $this->fromApp;
    }
}