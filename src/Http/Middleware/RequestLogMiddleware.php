<?php namespace Shambou\RequestLogs\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Shambou\RequestLogs\Jobs\LogRestRequest;

/**
 * If middleware is used as is request logs will always save:
 * action = undefined AND channel = undefined
 * To get this right, where ever you define middleware you should pass
 *
 * request()->attributes->add([
 *   'action'   => 'some-action-name',
 *   'channel'  => 'channel-name',
 *   'relation' => Eloquent Model instance ex: IrlEvent::find(1)
 *   'custom_data' => []
 * ]);
 */
class RequestLogMiddleware
{
    private float $startTime;

    public function __construct()
    {
        $this->startTime = microtime(true);
    }

    public function handle(Request $request, Closure $next)
    {
        /** @var JsonResponse|Response $response */
        $response = $next($request);

        $size = mb_strlen(serialize($response->getContent()), '8bit');
        $mbSize = $size / (1024*1024);

        // if size of response is over 5MB skip logging, just return response
        if ($mbSize > 5) {
            return $response;
        }

        if (! $response instanceof JsonResponse) {
            $response = new JsonResponse($response->getContent(), $response->status(), $response->headers->all());
        }

        dispatch(new LogRestRequest($response, $this->startTime));

        return $response;
    }
}
