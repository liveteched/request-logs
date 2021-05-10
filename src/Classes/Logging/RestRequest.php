<?php namespace Shambou\RequestLogs\Classes\Logging;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Arr;

/**
 * Basic/Rest ApiRequest class
 * Used for storing rest requests
 */
class RestRequest extends ApiRequest
{
    public function __construct(HttpRequest $request)
    {
        $this->headers = $this->parseHeaders($request);
        $this->body = json_encode($request->all());
        $this->url = $request->fullUrl();
    }

    public function toArray()
    {
        return [
            'headers' => $this->headers,
            'body'    => $this->body,
            'url'     => $this->url,
        ];
    }

    /**
     * Parses the current HTTP request headers into a string
     *
     * @param HttpRequest $request
     * @return string
     */
    private function parseHeaders(HttpRequest $request): string
    {
        $headers  = $request->getMethod().' '.$request->fullUrl().' '.$request->getProtocolVersion()."\n";

        // Parse current request headers into expected string format
        foreach ($request->headers->all() as $headerName => $headerValue) {
            $headers .= $headerName.": ".Arr::first($headerValue)."\n";
        }

        return $headers;
    }
}
