<?php

use \Gallery\App\Configuration;
use \Gallery\App\Converter\Service as Converter;

if ('cli' == PHP_SAPI) {

    date_default_timezone_set("Europe/Berlin");
    require __DIR__ . '/../vendor/autoload.php';

    $dotenv = new Dotenv\Dotenv(__DIR__ . "/../");
    $dotenv->overload();

    date_default_timezone_set(getenv('TIMEZONE'));

    if (Configuration::get('settings.debug')) {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
    }

    $args = [];
    foreach ($GLOBALS['argv'] as $argument) {
        if (preg_match('/\-\-(\w+)=([^ =]+)/', $argument, $match)) {
            $args[ $match[1] ] = $match[2];
        }
    }

    if (isset($args['service'])) {

        $service = $args['service'];
        switch ($service) {

            case 'converter':
                $converter = new Converter();
                if (isset($args['action'])) {

                    $action = $args['action'];
                    switch ($action) {

                        case 'read':
                            $converter->read();
                        case 'convert':
                            exit($converter->convert());
                            break;
                    }
                }
                break;
        }
    }

    throw new Exception(
        'service not supported or found'
    );
}
