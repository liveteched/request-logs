<?php namespace Shambou\RequestLogs\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Shambou\RequestLogs\Classes\Logging\Request;
use Shambou\RequestLogs\Classes\Logging\Response;
use SoapClient;

interface RequestLogInterface
{
    public function requestLogRelations(): HasMany;

    public function setRequest(Request $request): self;

    public function setResponse(Response $response): self;

    public function setJsonResponse(JsonResponse $response): self;

    public function setSoapResponse(SoapClient $soapClient): self;

    public function getRequest(): Request;

    public function getResponse(): Response;

    public function storeLog(array $data, $relation = null): self;
}
