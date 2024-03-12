<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;
$db = (new DB())->connect();

$customerController = new CustomerController($db);

$app->get('/GET/listclients', [$customerController, 'listClients']);

$app->post('/POST/createclient', [$customerController, 'createClient']);

$app->get('/GET/client/{dni}', [$customerController, 'getClient']);

$app->put('/DELETE/deleteclient/{dni}', [$customerController, 'deleteClient']);

$app->put('/PUT/updateclient/{dni}', [$customerController, 'updateClient']);

$app->put('/PUT/updateclientstate/{dni}', [$customerController, 'updateClientState']);

$app->get('/consultar-dni/{dni}', [$customerController, 'consultarDni']);
