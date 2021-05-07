<?php namespace Shambou\RequestLogs\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Shambou\RequestLogs\Classes\Logging\Request;
use Shambou\RequestLogs\Classes\Logging\Response;

interface RequestLogInterface
{
    public function requestLogRelations(): HasMany;

    public function setRequest(Request $request): self;

    public function setResponse(Response $response): self;

    public function getRequest(): Request;
    
    public function getResponse(): Response;

    public function storeLog(array $data, $relation = null): self;
}
