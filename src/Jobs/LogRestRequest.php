<?php namespace Shambou\RequestLogs\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Shambou\RequestLogs\Facades\RequestLogFactory;

class LogRestRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(private JsonResponse $response, private float $startTime)
    {
        if (config('requestlogs.queue')) {
            $this->queue = config('requestlogs.queue');
        }
    }

    public function handle(Request $request)
    {
        $requestLog = RequestLogFactory::buildFromCurrentRequest($request)
            ->setJsonResponse($this->response);

        $customData = $request->attributes->get('custom_data', null);

        $requestLog->storeLog([
            'action'           => $request->attributes->get('action', 'undefined'),
            'channel'          => $request->attributes->get('channel', 'undefined'),
            'method'           => $request->getMethod(),
            'execution_time'   => microtime(true) - $this->startTime,
            'custom_data'      => is_array($customData) ? json_encode($customData) : $customData
        ], $request->attributes->get('relation'));
    }
}
