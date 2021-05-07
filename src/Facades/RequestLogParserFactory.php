<?php namespace Shambou\RequestLogs\Facades;

use Illuminate\Support\Facades\Facade;
use Shambou\RequestLogs\Classes\Parsing\AbstractRequestLogParser;
use Shambou\RequestLogs\Models\RequestLog;

/**
 * @method static AbstractRequestLogParser buildFromRequestLog(RequestLog $requestLog)
 *
 * @see \Shambou\RequestLogs\Classes\Parsing\RequestLogParserFactory
 */
class RequestLogParserFactory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'requestlog_parser_factory';
    }
}
