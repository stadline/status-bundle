<?php

namespace StadLine\StatusPageBundle\ApiStatus;

class PublicApiStatusPage implements ApiStatusInterface
{
    /** @var  string */
    private $url;

    /** @var  string */
    private $name;

    /** @var  boolean */
    private $isAvailable;

    public function __construct()
    {
        $this->isAvailable = false;
    }

    /**
     * Sets is available.
     *
     * @param boolean
     */
    public function setIsAvailable($isAvailable)
    {
        return $this->isAvailable = $isAvailable;
    }

    /**
     * is api status page available.
     *
     * @return boolean
     */
    public function IsAvailable()
    {
        return $this->isAvailable;
    }

    /**
     * Sets the url.
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Gets the url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the name.
     *
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the public api status page name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
