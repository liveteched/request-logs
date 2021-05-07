<?php namespace Shambou\RequestLogs\Classes\Logging;

class Response
{
    protected ?string $headers = null;

    protected ?string $body = null;

    protected bool $isSuccessful;

    public function getHeaders(): ?string
    {
        return $this->headers;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function isSuccessful(): bool
    {
        return $this->isSuccessful;
    }
}
