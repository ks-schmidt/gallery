<?php

    require '../vendor/autoload.php';

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    use \Gallery\App\Configuration;
    use \Gallery\App\Gallery;

    $dotenv = new Dotenv\Dotenv(getenv("API_PATH"));
    $dotenv->overload();

    date_default_timezone_set(getenv('TIMEZONE'));

    $app = new \Slim\App(['settings' => Configuration::get('settings')]);
    $app->add(function ($request, $response, $next) {
        $uri = $request->getUri();
        $uri = $uri->withPath(strtolower($uri->getPath()));
        $request = $request->withUri($uri);

        return $next($request, $response);
    });

    // ERRORs

    if (!Configuration::get('settings.debug')) {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');

    } else {

        $c = $app->getContainer();

        $c['errorHandler'] = function () {
            return function (Request $request, Response $response, \Exception $exception) {
                $response->getBody()->rewind();

                return Configuration::get('view')->renderPartial(
                    $response->withStatus(500), '/../../public/style/error/html/500.html'
                );
            };
        };
        $c['phpErrorHandler'] = function () {
            return function (Request $request, Response $response, \Exception $exception) {
                $response->getBody()->rewind();

                return Configuration::get('view')->renderPartial(
                    $response->withStatus(500), '/../../public/style/error/html/500.html'
                );
            };
        };
        $c['notFoundHandler'] = function () {
            return function (Request $request, Response $response) {
                $response->getBody()->rewind();

                return Configuration::get('view')->renderPartial(
                    $response->withStatus(404), '/../../public/style/error/html/404.html'
                );
            };
        };
        $c['notAllowedHandler'] = function () {
            return function (Request $request, Response $response) {
                $response->getBody()->rewind();

                Configuration::get('view')->renderPartial(
                    $response->withStatus(403), '/../../public/style/error/html/403.html'
                );
            };
        };
    }

    // ROUTEs

    $app->get('/[{path:.*}]', function (Request $request, Response $response, $next) {
        return (new Gallery())->handle($request, $response);

    })->add(function (Request $request, Response $response, $next) {
        Configuration::get('logger')->info(
            'request started'
        );

        return $next($request, $response);

    })->add(function (Request $request, Response $response, $next) {
        $response = $next($request, $response);

        Configuration::get('logger')->info(
            'request finished with: ' . $response->getStatusCode()
        );

        return $response;
    });

    $app->run();
