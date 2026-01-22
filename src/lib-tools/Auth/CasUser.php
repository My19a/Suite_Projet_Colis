<?php

class CasUser
{
    private string $login;
    private string $displayName;
    private ?string $email;
    private array $attributes;

    public function __construct(string $login, string $displayName, ?string $email = null, array $attributes = [])
    {
        $this->login = $login;
        $this->displayName = $displayName;
        $this->email = $email;
        $this->attributes = $attributes;
    }

    public function getLogin(): string { return $this->login; }
    public function getDisplayName(): string { return $this->displayName; }
    public function getEmail(): ?string { return $this->email; }
    public function getAttributes(): array { return $this->attributes; }
}
