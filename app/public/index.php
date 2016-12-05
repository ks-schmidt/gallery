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
$config['outputBuffering'] = false;

$app = new \Slim\App(["settings" => $config]);

$templateDefaultVariables = [
    "title" => "Default Title",
];

$container = $app->getContainer();
$container['view'] = function ($container) {
    $view = new \Slim\View("../src/view/");
    $view->setLayout("common/layout/bootstrap.phtml");

    return $view;
};

$app->get('/gallery', function (Request $request, Response $response) {
    $response = $this->view->render($response, "gallery/index.phtml", ['content' => 'hello']);
    return $response;

});
$app->run();
