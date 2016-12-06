<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';
//require '../src/common/View.php';

// Environment variables
(new Dotenv\Dotenv(__DIR__ . '/../'))->load();

$config = [];
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

$templateDefaultVariables = [
    "title" => "gallery",
];

$container = $app->getContainer();
$container['view'] = function ($container) use ($templateDefaultVariables) {
    $view = new \Slim\View("../src/view/", $templateDefaultVariables);
    $view->setLayout("common/layout/bootstrap.phtml");

    return $view;
};

use \Gallery\controller\IndexController;
$container[\Gallery\controller\IndexController::class] = function ($c) {
    return new \Gallery\controller\IndexController;
};

$app->get('/', function (Request $request, Response $response) {
    $controller = new Gallery\controller\IndexController($this->getContainer());
    return $controller->index($request, $response);
});
$app->run();
