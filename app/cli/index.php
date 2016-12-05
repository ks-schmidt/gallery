<?php

if ('cli' == PHP_SAPI) {

    // Autoloader
    require_once __DIR__ . '/../vendor/autoload.php';

    // Environment variables
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $args = array();
    foreach ($GLOBALS['argv'] as $argument) {
        if (preg_match('/\-\-(\w+)=([^ =]+)/', $argument, $match)) {
            $args[$match[1]] = $match[2];
        }
    }

    if (isset($args['service'])) {
        switch ($args['service']) {
            case 'scan':

                exit($service->run($args));
                break;
        }
    }

    throw new Exception(
        'service not permitted or found'
    );
}
