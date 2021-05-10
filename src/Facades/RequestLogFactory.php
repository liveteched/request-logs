<?php namespace Shambou\RequestLogs\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Shambou\RequestLogs\Models\RequestLog;
use SoapClient;

/**
 * @method static RequestLog createForRest(Request $request)
 * @method static RequestLog createForSoap(string $url, SoapClient $soapClient)
 *
 * @see \Shambou\RequestLogs\Classes\Logging\RequestLogFactory
 */
class RequestLogFactory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'requestlog_factory';
    }
}
