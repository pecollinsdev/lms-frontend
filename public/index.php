<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/App.php';
require_once __DIR__ . '/../app/Core/Controller.php';

// Extract clean URL path (e.g., "auth/login")
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$basePath = rtrim(str_replace('\\', '/', $scriptName), '/');

$url = trim(str_replace($basePath, '', $requestUri), '/');
$url = explode('?', $url)[0]; // Remove query string

$_GET['url'] = $url;

$app = new \App\Core\App();
