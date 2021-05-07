<?php namespace Shambou\RequestLogs\Classes\Parsing;

use Shambou\RequestLogs\Models\RequestLog;

class RequestLogParserFactory
{
    public function buildFromRequestLog(RequestLog $requestLog): ?AbstractRequestLogParser
    {
        if (in_array($requestLog->channel, config('requestlogs.channels.json'))) {
            return new JsonRequestLogParser($requestLog);
        }

        if (in_array($requestLog->channel, config('requestlogs.channels.soap'))) {
            return new XmlRequestLogParser($requestLog);
        }
        
        return null;
    }
}
