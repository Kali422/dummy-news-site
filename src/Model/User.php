<?php

namespace DummyNewsSite\Model;

class User
{
    private int $id;

    private string $username;

    private string $password;

    private ?string $sessionId;

    public function __construct(int $id, string $username, string $password, ?string $sessionId = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->sessionId = $sessionId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }
}