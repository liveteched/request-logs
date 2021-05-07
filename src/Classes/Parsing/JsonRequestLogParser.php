<?php namespace Shambou\RequestLogs\Classes\Parsing;

class JsonRequestLogParser extends AbstractRequestLogParser
{
    public function requestData(): array
    {
        return json_decode($this->requestBody, true);
    }

    public function responseData()
    {
        if (is_string($this->responseBody) && ! is_json($this->responseBody)) {
            return $this->responseBody;
        }
        
        return json_decode($this->responseBody, true);
    }
}
