<?php

namespace Stadline\StatusPageBundle\ApiStatus;

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
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getExceptionMessage();
}
