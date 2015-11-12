<?php

namespace StadLine\StatusPageBundle\ApiStatus;

/**
 * The api status interface.
 */
interface ApiStatusInterface
{
    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return boolean
     */
    public function isAvailable();

    /**
     * @return return
     */
    public function getName();
}
