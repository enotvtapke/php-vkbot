<?php

declare(strict_types=1);

use App\Controllers\ServerHandler;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;

const PROJECT_ROOT = __DIR__ . '/../';
const RESOURCES_ROOT = PROJECT_ROOT . 'resources/';
require PROJECT_ROOT . 'vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);

if (false) { // TODO Remove if
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// TODO Opcache

$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

$body = file_get_contents('php://input');
$container->get(LoggerInterface::class)->info("Received request with body: $body");
$data = json_decode($body);
$container->get(ServerHandler::class)->parse($data);
header("HTTP/1.1 200 OK");
echo 'OK';
