<?php namespace Shambou\RequestLogs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Shambou\RequestLogs\Classes\Logging\ApiRequest;
use Shambou\RequestLogs\Classes\Logging\ApiResponse;
use Shambou\RequestLogs\Classes\Logging\RestResponse;
use Shambou\RequestLogs\Classes\Logging\SoapResponse;
use Shambou\RequestLogs\Contracts\RequestLogInterface;
use Shambou\RequestLogs\Facades\RequestLogParserFactory;
use SoapClient;

class RequestLog extends Model implements RequestLogInterface
{
    private ApiRequest $request;

    private ApiResponse $response;

    protected $fillable = ['url', 'action', 'channel', 'request_headers', 'request_body', 'response_headers', 'response_body', 'success', 'custom_data', 'execution_time'];

    protected $appends = ['parsed_request', 'parsed_response'];

    public function getParsedRequestAttribute(): string
    {
        $parser = RequestLogParserFactory::buildFromRequestLog($this);

        return $parser ? $parser->renderRequest() : $this->request_body;
    }

    public function getParsedResponseAttribute(): string
    {
        $parser = RequestLogParserFactory::buildFromRequestLog($this);

        return $parser ? $parser->renderResponse() : $this->response_body;
    }

    public function requestLogRelations(): HasMany
    {
        return $this->hasMany(RequestLogRelation::class);
    }

    public function setRequest(ApiRequest $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function setResponse(ApiResponse $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function setJsonResponse(JsonResponse $response): self
    {
        return $this->setResponse(new RestResponse($response));
    }

    public function setSoapResponse(SoapClient $soapClient): self
    {
        return $this->setResponse(new SoapResponse($soapClient));
    }

    public function getRequest(): ApiRequest
    {
        return $this->request;
    }

    public function getResponse(): ApiResponse
    {
        return $this->response;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function setCustomData(array $data): self
    {
        $this->custom_data = json_encode($data);

        return $this;
    }

    public function setExecutionTime($executionTime): self
    {
        $this->execution_time = $executionTime;

        return $this;
    }

    /**
     * @param mixed|Model|Model[] $relations model relation/s
     */
    public function storeLog($relations = null): void
    {
        $this->fill([
            'channel'          => $this->channel ?? 'default',
            'action'           => $this->action ?? 'default',
            'url'              => $this->getRequest()->getUrl(),
            'request_headers'  => $this->getRequest()->getHeaders(),
            'request_body'     => $this->getRequest()->getBody(),
            'response_headers' => $this->getResponse()->getHeaders(),
            'response_body'    => $this->getResponse()->getBody(),
            'success'          => $this->getResponse()->isSuccessful(),
        ])->save();

        if ($relations) {
            if (is_iterable($relations)) {
                foreach ($relations as $model) {
                    RequestLogRelation::create([
                        'request_log_id' => $this->id,
                        'relatable_type' => get_class($model),
                        'relatable_id' => $model->id
                    ]);
                }
            } else {
                RequestLogRelation::create([
                    'request_log_id' => $this->id,
                    'relatable_type' => get_class($relations),
                    'relatable_id' => $relations->id,
                ]);
            }
        }
    }
}
