<?php

namespace Stadline\StatusPageBundle\ApiStatus;

use Exception;

class PublicApiStatusPage implements ApiStatusInterface
{
    const STATUS_CODE_OK = 200;

    /** @var string */
    private $url;

    /** @var string */
    private $name;

    /** @var int */
    private $statusCode;

    /** @var string */
    private $exceptionMessage;

    /**
     * @param string $name
     * @param string $url
     */
    public function __construct($name, $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * is api status page available.
     *
     * @return boolean
     */
    public function IsAvailable()
    {
        try {
            $this->doRequest();
            return true;
        } catch (\Exception $e) {
            $this->exceptionMessage = $e->getMessage();
        }
        return false;
    }

    /**
     * Request API Url and throw exception if code not 200
     * @throws Exception
     */
    private function doRequest()
    {
        $headers = @get_headers($this->url);
        if (!$headers) {
            throw new Exception("Could not resolve host");
        }
        $this->statusCode = substr($headers[0], 9, 3);
        if ($this->statusCode != self::STATUS_CODE_OK) {
            throw new Exception($headers[0]);
        }
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
     * Get the public api status page name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the exception message.
     *
     * @return string
     */
    public function getExceptionMessage()
    {
        return $this->exceptionMessage;
    }
}
