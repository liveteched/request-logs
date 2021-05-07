<?php namespace Shambou\RequestLogs\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Shambou\RequestLogs\Classes\Logging\RestResponse;
use Shambou\RequestLogs\Facades\RequestLogFactory;

class LogRestRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var RestResponse
     */
    private $response;

    /**
     * @var float
     */
    private $startTime;

    /**
     * @param JsonResponse $response
     * @param float        $startTime
     */
    public function __construct(JsonResponse $response, float $startTime)
    {
        $this->response = $response;
        $this->startTime = $startTime;
    }

    /**
     * @param Request  $request
     */
    public function handle(Request $request)
    {
        $requestLog = RequestLogFactory::buildFromCurrentRequest($request)
            ->setResponse(new RestResponse($this->response));

        $customData = $request->attributes->get('custom_data', null);
        
        $requestLog->storeLog([
            'url'              => $requestLog->getRequest()->getUrl(),
            'action'           => $request->attributes->get('action', 'undefined'),
            'channel'          => $request->attributes->get('channel', 'undefined'),
            'method'           => $request->getMethod(),
            'request_headers'  => $requestLog->getRequest()->getHeaders(),
            'request_body'     => $requestLog->getRequest()->getBody(),
            'response_headers' => $requestLog->getResponse()->getHeaders(),
            'response_body'    => $requestLog->getResponse()->getBody(),
            'success'          => $requestLog->getResponse()->isSuccessful(),
            'execution_time'   => microtime(true) - $this->startTime,
            'custom_data'      => is_array($customData) ? json_encode($customData) : $customData
        ], $request->attributes->get('relation'));
    }
}
