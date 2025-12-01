<?php

namespace SAE\Auth;

class CasConfiguration
{
    private string $host;
    private string $context;
    private int $port;
    private ?string $caCertPath;

    public function __construct(
        string $host,
        string $context = '/cas/',
        int $port = 443,
        ?string $caCertPath = null
    ) {
        $this->host = $host;
        $this->context = $context;
        $this->port = $port;
        $this->caCertPath = $caCertPath;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getCaCertPath(): ?string
    {
        return $this->caCertPath;
    }

}
