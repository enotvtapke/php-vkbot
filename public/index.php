<?php

declare(strict_types=1);

use App\Controllers\ServerHandler;
use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

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

$data = json_decode(file_get_contents('php://input'));
$container->get(ServerHandler::class)->parse($data);
