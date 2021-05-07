<?php namespace Shambou\RequestLogs\Classes\Logging;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Shambou\RequestLogs\Models\RequestLog;
use SoapClient;

class RequestLogFactory
{

    /**
     * Creates a request / response for current PHP session
     * Used for logging requests to our API
     * Sets empty response which can be overridden with the setResponse method
     *
     * @param HttpRequest $request
     * @return RequestLog
     */
    public function buildFromCurrentRequest(HttpRequest $request): RequestLog
    {
        return (new RequestLog)->setRequest(new RestRequest($request))
            ->setResponse(new RestResponse(new JsonResponse));
    }

    public function buildFromSoapClient(string $url, SoapClient $soapClient): RequestLog
    {
        return (new RequestLog)->setRequest(new SoapRequest($url, $soapClient))
            ->setResponse(new SoapResponse($soapClient));
    }
}
