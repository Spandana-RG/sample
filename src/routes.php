<?php

use Slim\Http\Request;
use Slim\Http\Response;


require __DIR__ . '/../src/routes/repositories.php';
require __DIR__ . '/../src/routes/import.php';

// Routes

$app->get('/install.php', function (Request $request, Response $response, array $args) {
   return $this->renderer->render($response, "../install.php");
});

$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->renderer->render($response, "/index.phtml");
});
