<?php namespace Shambou\RequestLogs\Classes\Logging;

class Request
{
    protected ?string $headers = null;

    protected ?string $body = null;

    protected string $url;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getHeaders(): ?string
    {
        return $this->headers;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }
}
