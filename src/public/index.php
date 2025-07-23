<?php

use Dotenv\Dotenv;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__.'/../utils/Response.php';
require_once __DIR__.'/../utils/Validator.php';
require_once __DIR__ . '/../routes/api.php';

require __DIR__.'/../vendor/autoload.php';

Dotenv::createImmutable(__DIR__.'/..')->load();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

if (($pos = strpos($requestUri, '?')) !== false) $requestUri = substr($requestUri, 0, $pos);

$router = new ApiRouter();
$router->handleRequest($requestMethod, $requestUri);