<?php namespace Shambou\RequestLogs\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Shambou\RequestLogs\Classes\Logging\ApiRequest;
use Shambou\RequestLogs\Classes\Logging\Response;
use SoapClient;

interface RequestLogInterface
{
    public function requestLogRelations(): HasMany;

    public function setRequest(ApiRequest $request): self;

    public function setResponse(Response $response): self;

    public function setJsonResponse(JsonResponse $response): self;

    public function setSoapResponse(SoapClient $soapClient): self;

    public function getRequest(): ApiRequest;

    public function getResponse(): Response;

    public function setChannel(string $channel): self;

    public function setAction(string $action): self;

    public function setCustomData(array $data): self;

    public function setExecutionTime($executionTime): self;

    public function storeLog($relations = null): void;
}
