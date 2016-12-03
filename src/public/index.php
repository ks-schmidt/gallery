<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

$config = [];
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();
$container['view'] = new \Slim\Views\PhpRenderer("../templates/");

$app->get('/scan', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');

    $dir = new RecursiveIteratorIterator(
        new RecursiveRegexIterator(
            new RecursiveDirectoryIterator(
                '/var/services/media/media/photo/'
            ),
            '#(?<!/)\.jpg$|^[^\.]*$#i'
        ),
        true
    );

//    $Directory = new RecursiveDirectoryIterator('/var/services/media/media/photo/');
//    $Iterator = new RecursiveIteratorIterator($Directory, true);
//    $Regex = new RegexIterator($Iterator, '/^.+\.jpg$/i', RecursiveRegexIterator::GET_MATCH);    

    $it = 0;
    foreach ($dir as $file) {
        echo sprintf("%s</br>\n", $file->getPathname());
        $it++;
    }

    echo $it;

    $response = $this->view->render($response, "index.phtml", ["files" => $files]);

    return $response;

});
$app->run();
