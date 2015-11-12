<?php

namespace StadLine\StatusPageBundle\ApiStatus;

abstract class ApiStatusFactory
{
    /**
     * The factory creates an instance of ApiStatusInterface;
     *
     * @param ApiStatusInterface
     * @return ApiStatusInterface
     */
    public static function create($parameters)
    {
        if ($parameters['url'] && $parameters['name']) {
            $api = new PublicApiStatusPage();
            $api->setName($parameters['name']);
            $api->setUrl($parameters['url']);

            return $api;
        }
    }
}
