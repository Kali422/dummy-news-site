<?php

namespace DummyNewsSite\Controller;

use DummyNewsSite\Model\User;
use DummyNewsSite\Repository\LoginRepository;
use DummyNewsSite\Repository\SQLiteConnection;
use DummyNewsSite\Service\LoginService;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AbstractController
{
    protected Environment $twig;

    protected LoginService $loginService;

    public function __construct(string $templatesLocalization)
    {
        $this->twig = new Environment(new FilesystemLoader($templatesLocalization));
        $dbConnection = (new SQLiteConnection())->connect();
        $this->loginService = new LoginService(new LoginRepository($dbConnection));
    }

    protected function verifyLogin(): User
    {
        if (!isset($_SESSION['username'])) {
            header('Location: /login');
            die();
        }

        $user = $this->loginService->sessionLogin(
            htmlspecialchars(session_id()),
            htmlspecialchars($_SESSION['username'])
        );

        if ($user === null) {
            session_destroy();
            header('Location: /login');
            die();
        }

        return $user;
    }
}