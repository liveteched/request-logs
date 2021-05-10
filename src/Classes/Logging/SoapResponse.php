<?php namespace Shambou\RequestLogs\Classes\Logging;

use SoapClient;

class SoapResponse extends ApiResponse
{
    public function __construct(SoapClient $soapClient)
    {
        $this->body    = $soapClient->__getLastResponse();
        $this->headers = $soapClient->__getLastResponseHeaders();
        $this->isSuccessful = $this->getLastResponseCode() == 200;
    }

    public function getLastResponseCode(): int
    {
        preg_match("/HTTP\/\d\.\d\s*\K[\d]+/", $this->headers, $matches);

        return (is_array($matches) and isset($matches[0])) ? (int) $matches[0] : 0;
    }
}
