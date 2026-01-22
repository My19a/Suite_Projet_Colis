<?php

class User
{
    private int $id;
    private string $uid_cas;
    private string $fullName;
    private string $email;
    private string $role;
    private ?int $departement_id;
    private array $casAttributes;

    public function __construct(
        int $id,
        string $uid_cas,
        string $fullName,
        string $email,
        string $role,
        ?int $departement_id = null,
        array $casAttributes = []
    ) {
        $this->id = $id;
        $this->uid_cas = $uid_cas;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->role = $role;
        $this->departement_id = $departement_id;
        $this->casAttributes = $casAttributes;
    }

    public function getId(): int { return $this->id; }
    public function getUidCas(): string { return $this->uid_cas; }
    public function getFullName(): string { return $this->fullName; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string { return $this->role; }
    public function getDepartementId(): ?int { return $this->departement_id; }
    public function getCasAttributes(): array { return $this->casAttributes; }

    public function hasRole(string $role): bool { return $this->role === $role; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isPostalIut(): bool { return $this->role === 'postal_iut'; }
    public function isPostalUniv(): bool { return $this->role === 'postal_univ'; }
    public function isFinance(): bool { return $this->role === 'finance'; }
    public function isDirecteur(): bool { return $this->role === 'directeur'; }
    public function isDepartement(): bool { return $this->role === 'departement'; }
}
