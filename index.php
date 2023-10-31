<?php

use DummyNewsSite\Router\Router;

session_start();

require_once 'vendor/autoload.php';

$router = new Router();

$router->handle($_SERVER['REQUEST_METHOD'], trim($_SERVER['REQUEST_URI'], '\?'));
