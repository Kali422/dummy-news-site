<?php

namespace DummyNewsSite\Router;

use DummyNewsSite\Controller\LoginController;
use DummyNewsSite\Controller\NewsController;
use DummyNewsSite\Repository\NewsRepository;
use DummyNewsSite\Repository\SQLiteConnection;

class Router
{
    private LoginController $loginController;

    private NewsController $newsController;

    public function __construct()
    {
        $this->loginController = new LoginController(__DIR__.'/../Views');
        $this->newsController = new NewsController(
            __DIR__.'/../Views',
            new NewsRepository((new SQLiteConnection())->connect())
        );
    }

    public function handle(string $httpMethod, string $uri): void
    {
        if ($httpMethod === 'GET') {
            if ($uri === '/login') {
                echo $this->loginController->loginPage();

                return;
            }
            if ($uri === '/logout') {
                $this->loginController->logout();

                return;
            }
            if ($uri === '/' || $uri === '/news') {
                echo $this->newsController->list();

                return;
            }
        }

        if ($httpMethod === 'POST') {
            if ($uri === '/login') {
                $this->loginController->login();

                return;
            }
            if ($uri === '/createNews') {
                $this->newsController->create();

                return;
            }
            if ($uri === '/deleteNews') {
                $this->newsController->delete();

                return;
            }
            if ($uri === '/editNews') {
                $this->newsController->edit();

                return;
            }
        }

        header('HTTP/1.0 404 Not Found', true, 404);
    }

}