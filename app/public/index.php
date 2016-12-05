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

$app->get('/gallery', function (Request $request, Response $response) {
    $response = $this->view->render($response, "gallery/index.phtml", ['content' => 'gallery']);
    return $response;

});
$app->run();
