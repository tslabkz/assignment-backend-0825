<?php

require_once __DIR__ . '/vendor/autoload.php'; 

use Symfony\Component\Dotenv\Dotenv; 

$dotenv = new Dotenv(); 
$dotenv->loadEnv(__DIR__ . '/.env'); 

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 

if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    echo "<h1>Welcome to the Assignment Backend 0825</h1>";
    echo "<p>Current request URI: <strong>{$requestUri}</strong></p>";
    echo "<p>To access the application, please navigate to <a href='/pages/users/insert.php'>Insert User Page</a>.</p>";
    exit;
} 
