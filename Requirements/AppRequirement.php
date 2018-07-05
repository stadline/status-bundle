<?php

namespace Stadline\StatusPageBundle\Requirements;

use Stadline\StatusPageBundle\Services\Requirement;

class AppRequirement extends Requirement
{
    const INFORMATIVE = false;
    const DEPENDANT = false;
    const FROM_APP = true;

    protected $informative;
    protected $dependant;
    protected $fromApp;

    /**
     * Constructor that initializes the requirement.
     *
     * @param bool           $fulfilled   Whether the requirement is fulfilled
     * @param string         $testMessage The message for testing the requirement
     * @param string         $helpHtml    The help text formatted in HTML for resolving the problem
     * @param string|null    $helpText    The help text (when null, it will be inferred from $helpHtml, i.e. stripped from HTML tags)
     * @param bool           $optional    Whether this is only an optional recommendation not a mandatory requirement
     * @param array<boolean> $types       Three booleans (informative, dependant, fromApp)
     */
    public function __construct($fulfilled, $testMessage, $helpHtml, $helpText = null, $optional = false, $types = [
        self::INFORMATIVE,
        self::DEPENDANT,
        self::FROM_APP
    ])
    {
        parent::__construct($fulfilled, $testMessage, $helpHtml, $helpText, $optional);

        $this->informative = (bool) $types[0];
        $this->dependant = (bool) $types[1];
        $this->fromApp = (bool) $types[2];
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