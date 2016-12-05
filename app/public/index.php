<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

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
$container['view'] = new \Slim\Views\PhpRenderer("../templates/", $templateDefaultVariables);

$app->get('/gallery', function (Request $request, Response $response) {

    $dir = new RecursiveIteratorIterator(
        new RecursiveRegexIterator(
            new RecursiveDirectoryIterator(
                '/var/services/photo/LOMO'
            ),
            '#(?<!/)\.jpg$|^[^\.]*$#i'
        ),
        true
    );

    $it = 0;
    foreach ($dir as $file) {
        echo sprintf("%s</br>\n", $file->getPathname());
        $it++;
    }

    echo $it;

    $response = $this->view->render($response, "index.phtml", []);

    return $response;

});
$app->run();
