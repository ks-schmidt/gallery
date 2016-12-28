<?php

date_default_timezone_set("Europe/Berlin");

require '../vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Gallery\App\View\PhpRenderer;

$dotenv = new Dotenv\Dotenv(__DIR__."/../");
$dotenv->overload();

$app = new \Slim\App(require __DIR__ . '/../src/config.php');
if ($app->getContainer()->get('settings')['debug']) {
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);

    $app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);
}

$c = $app->getContainer();
$c['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'] . "/info.log", "info"));
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'] . "/debug.log", "debug"));
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'] . "/warning.log", "warning"));
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'] . "/error.log", "error"));

    return $logger;
};
$c['view'] = function ($c) {
    $view = new PhpRenderer("../src/View/");
    $view->setLayout("common/layout/bootstrap.phtml");

    return $view;
};

$app->get('/[{path:.*}]', '\Gallery\App\Gallery:handle')->add(function ($request, $response, $next) {
    $uri = $request->getUri();
    $uri = $uri->withPath(strtolower($uri->getPath()));
    $request = $request->withUri($uri);

    // global middleware
    // ... CASE INSENSITIVE ROUTE
    return $next($request, $response);

})->add(function (Request $request, Response $response, $next) {
    $this->logger->info(
        'request started'
    );

    // log
    // ... BEFORE HANDLING REQUEST
    return $next($request, $response);

})->add(function (Request $request, Response $response, $next) {
    $response = $next($request, $response);

    // log
    // ... AFTER HANDLING REQUEST
    $this->logger->info(
        'request finished with: ' . $response->getStatusCode()
    );

    return $response;
});

$app->run();
