<?php namespace Shambou\RequestLogs\Classes\Logging;

use SoapClient;

class SoapRequest extends Request
{
    public function __construct(string $url, SoapClient $soapClient)
    {
        $this->url = $url;
        $this->headers = $soapClient->__getLastRequestHeaders();
        $this->body = $soapClient->__getLastRequest();
    }
}
