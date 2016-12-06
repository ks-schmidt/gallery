<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Gallery\IndexController;

require '../../vendor/autoload.php';

$dotEnv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotEnv->load();

$app = new \Slim\App(["settings" => []]);

$templateDefaultVariables = [
    "title" => "gallery",
];

$container = $app->getContainer();
$container['view'] = function ($container) {
    $view = new \Slim\View("../src/view/");
    $view->setLayout("common/layout/bootstrap.phtml");
    return $view;
};

IndexController::registerRoutes($app);

$app->run();
