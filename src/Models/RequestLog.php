<?php namespace Shambou\RequestLogs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shambou\RequestLogs\Classes\Logging\Request;
use Shambou\RequestLogs\Classes\Logging\Response;
use Shambou\RequestLogs\Classes\Logging\RestResponse;
use Shambou\RequestLogs\Classes\Logging\SoapResponse;
use Shambou\RequestLogs\Contracts\RequestLogInterface;
use Shambou\RequestLogs\Facades\RequestLogParserFactory;
use Illuminate\Http\JsonResponse;
use SoapClient;

class RequestLog extends Model implements RequestLogInterface
{
    private Request $request;

    private Response $response;

    protected $fillable = [
        'url',
        'action',
        'channel',
        'method',
        'request_headers',
        'request_body',
        'response_headers',
        'response_body',
        'success',
        'custom_data',
        'execution_time',
    ];

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

    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function setResponse(Response $response): self
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

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function storeLog(array $data, $relation = null): self
    {
        $data = array_merge([
            'url'              => $this->getRequest()->getUrl(),
            'request_headers'  => $this->getRequest()->getHeaders(),
            'request_body'     => $this->getRequest()->getBody(),
            'response_headers' => $this->getResponse()->getHeaders(),
            'response_body'    => $this->getResponse()->getBody(),
            'success'          => $this->getResponse()->isSuccessful(),
        ], $data);

        $this->fill($data)->save();

        if ($relation) {
            if (is_iterable($relation)) {
                foreach ($relation as $model) {
                    RequestLogRelation::create([
                        'request_log_id' => $this->id,
                        'relatable_type' => get_class($model),
                        'relatable_id'   => $model->id,
                    ]);
                }
            } else {
                RequestLogRelation::create([
                    'request_log_id' => $this->id,
                    'relatable_type' => get_class($relation),
                    'relatable_id'   => $relation->id,
                ]);
            }
        }

        return $this;
    }

    public static function getChannelsArray(): array
    {
        return self::select('channel')
            ->distinct()
            ->get()
            ->pluck('channel')
            ->toArray();
    }

    public static function getActionsGroupedByChannel(): array
    {
        $data = self::select('channel', 'action')
            ->groupby('action')
            ->get();

        $result = [];

        foreach ($data as $action) {
            $result[$action->channel][] = $action->action;
        }

        return $result;
    }
}
