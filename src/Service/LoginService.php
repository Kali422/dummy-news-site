<?php

namespace DummyNewsSite\Service;

use DummyNewsSite\Repository\LoginRepository;
use DummyNewsSite\Model\User;
use DummyNewsSite\Service\Exception\UserLoginErrorException;

class LoginService
{
    private LoginRepository $loginRepository;

    public function __construct(LoginRepository $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    public function credentialsLogin(string $username, string $password): ?User
    {
        $user = $this->loginRepository->getUserByUsername($username);
        if ($user !== null) {
            if (false === password_verify($password, $user->getPassword())) {
                throw new UserLoginErrorException("Wrong username or password");
            }
            $sessionId = htmlspecialchars(session_id());
            if ($this->loginRepository->sessionExists($sessionId)) {
                session_regenerate_id(true);
                $sessionId = htmlspecialchars(session_id());
            }

            $user->setSessionId($sessionId);
            $_SESSION['username'] = $user->getUsername();
            $this->loginRepository->insertSession($sessionId, $user->getId());
        } else {
            throw new UserLoginErrorException("Wrong username or password");
        }

        return $user;
    }

    public function sessionLogin(string $sessionId, string $username): ?User
    {
        $user = $this->loginRepository->getUserBySessionId($sessionId);
        if ($user !== null && $user->getUsername() !== $username) {
            return null;
        }

        return $user;
    }

    public function destroySession(): void
    {
        $this->loginRepository->deleteSession(htmlspecialchars(session_id()));
        session_destroy();
    }

}