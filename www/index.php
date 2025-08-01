<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// print_r($requestUri); die(); 

// switch ($requestUri) { 
//     case '/': 
//         require __DIR__ . '/pages/home.php'; 
//         break; 
//     case '/about': 
//         require __DIR__ . '/pages/about.php'; 
//         break; 
//     case '/about': 
//         require __DIR__ . '/pages/about.php'; 
//         break; 
//     default: 
//         http_response_code(404); 
//         echo "<h1>404 - Not Found</h1>"; 
//         break; 
// } 