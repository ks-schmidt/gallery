<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

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

require "../src/common/Controller.php";
require "../src/modules/gallery/controller/IndexController.php";
\Gallery\IndexController::registerRoutes($app);

$app->run();
