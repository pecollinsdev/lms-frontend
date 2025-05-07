<?php

namespace App\Core;

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Special handling for /auth/register
        if (
            isset($url[0], $url[1]) &&
            strtolower($url[0]) === 'auth' &&
            strtolower($url[1]) === 'register'
        ) {
            $controllerClass = '\App\Controllers\AuthController';
            $controller = new $controllerClass;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->register();
            } else {
                $controller->showRegister();
            }
            exit;
        }

        // Special handling for /auth/login
        if (
            isset($url[0], $url[1]) &&
            strtolower($url[0]) === 'auth' &&
            strtolower($url[1]) === 'login'
        ) {
            $controllerClass = '\App\Controllers\AuthController';
            $controller = new $controllerClass;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->login();
            } else {
                $controller->showLogin();
            }
            exit;
        }

        // Special handling for /auth/forgot-password
        if (
            isset($url[0], $url[1]) &&
            strtolower($url[0]) === 'auth' &&
            strtolower($url[1]) === 'forgot-password'
        ) {
            $controllerClass = '\App\Controllers\AuthController';
            $controller = new $controllerClass;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->forgotPassword();
            } else {
                $controller->showForgotPassword();
            }
            exit;
        }

        // Special handling for /instructor/courses/create
        if (
            isset($url[0], $url[1], $url[2]) &&
            strtolower($url[0]) === 'instructor' &&
            strtolower($url[1]) === 'courses' &&
            strtolower($url[2]) === 'create'
        ) {
            $controllerClass = '\App\Controllers\InstructorController';
            $controller = new $controllerClass;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->createCourse();
            } else {
                $controller->createCourse();
            }
            exit;
        }

        // Handle instructor assignment submissions route
        if (
            isset($url[0], $url[1], $url[2], $url[3], $url[4], $url[5]) &&
            strtolower($url[0]) === 'instructor' &&
            strtolower($url[1]) === 'courses' &&
            is_numeric($url[2]) &&
            strtolower($url[3]) === 'assignments' &&
            is_numeric($url[4]) &&
            strtolower($url[5]) === 'submissions'
        ) {
            $controllerClass = '\App\Controllers\InstructorController';
            $controller = new $controllerClass;
            $controller->submissions($url[2], $url[4]);
            exit;
        }

        // Handle instructor course assignments route
        if (
            isset($url[0], $url[1], $url[2], $url[3]) &&
            strtolower($url[0]) === 'instructor' &&
            strtolower($url[1]) === 'courses' &&
            is_numeric($url[2]) &&
            strtolower($url[3]) === 'assignments'
        ) {
            $controllerClass = '\App\Controllers\InstructorController';
            $controller = new $controllerClass;
            $controller->assignments($url[2]);
            exit;
        }

        // Check controller
        if (isset($url[0]) && file_exists('../app/Controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        $controllerClass = '\\App\\Controllers\\' . $this->controller;
        $this->controller = new $controllerClass;

        // Check method
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // Params
        $this->params = $url ? array_values($url) : [];

        // Call method with params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }

        return [];
    }
}
