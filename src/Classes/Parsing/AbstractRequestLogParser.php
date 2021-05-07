<?php namespace Shambou\RequestLogs\Classes\Parsing;

use Shambou\RequestLogs\Models\RequestLog;

abstract class AbstractRequestLogParser
{
    protected string $requestBody;
    protected string $responseBody;

    public function __construct(RequestLog $requestLog)
    {
        $this->requestBody  = $requestLog->request_body;
        $this->responseBody = $requestLog->response_body;
    }
    
    abstract public function requestData();
    abstract public function responseData();
    
    public function renderResponse(): string
    {
        $data = $this->responseData();
        
        if (empty($data)) {
            return 'Cannot parse response';
        }

        if (is_string($data)) {
            return $data;
        }

        return $this->render($data);
    }

    public function renderRequest(): string
    {
        $data = $this->requestData();
        
        if (empty($data)) {
            return 'Cannot parse request';
        }

        if (is_string($data)) {
            return $data;
        }

        return $this->render($data);
    }

    protected function render(array $data, $level = 0): string
    {
        $result = '';

        foreach ($data as $column => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            if (is_array($value)) {
                $value = PHP_EOL.$this->render($value, $level+1);
            }
            
            $result .= str_repeat('  ', $level).$column.': '.$value.PHP_EOL;
        }
        return rtrim($result);
    }
    
    protected function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
