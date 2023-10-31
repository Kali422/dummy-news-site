<?php

namespace DummyNewsSite\Controller;

use DummyNewsSite\Service\Exception\UserLoginErrorException;

class LoginController extends AbstractController
{
    public function loginPage(): string
    {
        if (isset($_SESSION['username'])) {
            header('Location: /');
            die();
        }

        $loginError = $_SESSION['loginError'];
        unset($_SESSION['loginError']);

        return $this->twig->render('login.html.twig', ['loginError' => $loginError]);
    }

    public function login(): string
    {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        try {
            $this->loginService->credentialsLogin($username, $password);
            header('Location: /');
            die();
        } catch (UserLoginErrorException $exception) {
            $_SESSION['loginError'] = $exception->getMessage();
            header('Location: /login');
            die();
        }
    }

    public function logout(): void
    {
        $this->loginService->destroySession();
        header('Location: /login');
        die();
    }
}