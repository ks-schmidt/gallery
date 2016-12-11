<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

(new Dotenv\Dotenv(__DIR__ . '/../'))->load();

$config = [];
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();
$container['view'] = function ($container) {
    $view = new \Slim\View("../src/view/");
    $view->setLayout("common/layout/bootstrap.phtml");

    return $view;
};

$app->get('/[{path:.*}]', function (Request $request, Response $response) use ($container) {

    $service = new Gallery\Gallery\Service($request, $response, $container);

    return $this->view->render(
        $response, "gallery/index.phtml", [
            'breadcrumb' => $service->getNavigation(),

            'folders' => $service->getFolders(),
            'files'   => $service->getFiles(),
        ]
    );
});


$app->run();
