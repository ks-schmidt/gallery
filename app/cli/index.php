<?php

if ('cli' == PHP_SAPI) {

    date_default_timezone_set("Europe/Berlin");
    require __DIR__ . '/../vendor/autoload.php';


    $dotenv = new Dotenv\Dotenv(__DIR__."/../");
    $dotenv->overload();

    $config = require __DIR__ . '/../src/config.php';

    if ($config['settings']['debug']) {
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ALL);
    }

    $args = [];
    foreach ($GLOBALS['argv'] as $argument) {
        if (preg_match('/\-\-(\w+)=([^ =]+)/', $argument, $match)) {
            $args[ $match[1] ] = $match[2];
        }
    }

    if (isset($args['service'])) {
        switch ($args['service']) {
            case 'convert':

                $app = new \Slim\App(require __DIR__ . '/../src/config.php');
                $c = $app->getContainer();

                $service = new Gallery\App\Converter\Job($c);

                exit($service->run($args));
                break;
        }
    }

    throw new Exception(
        'service not permitted or found'
    );
}
