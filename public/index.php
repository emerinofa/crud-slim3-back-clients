<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require __DIR__ . '/../config/bd.php';
require '../helpers/validators.php';
require '../controllers/clients.controller.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = new \Slim\App;

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Hello World!");

    return $response;
});

// Customers routes
require __DIR__ . '/../routes/customers.routes.php';

$app->run();

