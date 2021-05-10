<?php namespace Shambou\RequestLogs\Classes\Logging;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class RestResponse extends ApiResponse
{
    public function __construct(JsonResponse $response)
    {
        $this->headers = $this->parseHeaders($response);
        $this->body = $response->content();
        $this->isSuccessful = $response->getStatusCode() == 200;
    }

    private function parseHeaders(JsonResponse $response): string
    {
        $headers = '';
        // Parse current request headers into expected string format
        foreach ($response->headers->all() as $headerName => $headerValue) {
            $headers .= $headerName.": ".Arr::first($headerValue)."\n";
        }

        return $headers;
    }
}
