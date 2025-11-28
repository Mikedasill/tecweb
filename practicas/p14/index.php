<?php
require __DIR__ . '/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

/**
 * MUY IMPORTANTE:
 * Tu proyecto está en /tecweb/practicas/p14,
 * así que le decimos a Slim cuál es su base.
 */
$app->setBasePath('/tecweb/practicas/p14');

/* ---------- GET / ---------- */
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Hola mundo Slim");
    return $response;
});

/* ---------- GET /hola/{nombre} ---------- */
$app->get('/hola/{nombre}', function (Request $request, Response $response, array $args) {
    $nombre = $args['nombre'] ?? 'sin nombre';
    $response->getBody()->write("Hola " . $nombre);
    return $response;
});

/* ---------- POST /pruebapost ---------- */
$app->post('/pruebapost', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $nombre = $data['nombre'] ?? 'No enviado';
    $mensaje = "Recibido por POST: " . $nombre;

    $response->getBody()->write($mensaje);
    return $response;
});

/* ---------- POST /testjson ---------- */
$app->post('/testjson', function (Request $request, Response $response) {
    $data = $request->getParsedBody();

    $json = [
        "status"  => "success",
        "mensaje" => "Datos recibidos correctamente",
        "data"    => $data
    ];

    $response->getBody()->write(json_encode($json));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
