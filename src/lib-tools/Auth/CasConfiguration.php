<?php

class CasConfiguration
{
    private string $host;
    private string $context;
    private int $port;
    private ?string $caCertPath;
    private string $serviceBaseUrl;
    private bool $changeSessionId;

    public function __construct(
        string $host,
        string $context = '/cas/',
        int $port = 443,
        ?string $caCertPath = null,
        string $serviceBaseUrl = '',
        bool $changeSessionId = true
    ) {
        $this->host = $host;
        $this->context = $context;
        $this->port = $port;
        $this->caCertPath = $caCertPath;
        $this->serviceBaseUrl = $serviceBaseUrl;
        $this->changeSessionId = $changeSessionId;
    }

    public function getHost(): string { return $this->host; }
    public function getContext(): string { return $this->context; }
    public function getPort(): int { return $this->port; }
    public function getCaCertPath(): ?string { return $this->caCertPath; }
    public function getServiceBaseUrl(): ?string { return $this->serviceBaseUrl; }
    public function shouldChangeSessionId(): bool { return $this->changeSessionId; }

    public static function fromArray(array $casConfig, string $baseUrl): self
    {
        return new self(
            $casConfig['host'],
            $casConfig['context'] ?? '/cas/',
            $casConfig['port'] ?? 443,
            $casConfig['ca_cert_path'] ?? null,
            $baseUrl
        );
    }
}
