<?php

namespace Gallery\App;

use Monolog;
use \Doctrine\DBAL\DriverManager;
use \Gallery\App\View\PhpRenderer;

class Configuration
{
    private static $c;

    private static function init()
    {
        self::$c = require __DIR__ . '/../config.php';
        self::$c['logger'] = function ($c) {
            $settings = self::get('settings.logger');
            $logger = new Monolog\Logger($settings['name']);
            $logger->pushProcessor(new Monolog\Processor\UidProcessor());
            $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'] . "/info.log", "info", true));
            $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'] . "/debug.log", "debug", true));
            $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'] . "/error.log", "error", true));

            return $logger;
        };

        self::$c['db'] = DriverManager::getConnection([
            'driver' => 'pdo_mysql',

            'user'     => Configuration::get("db.mysql.user"),
            'password' => Configuration::get("db.mysql.password"),
            'host'     => Configuration::get("db.mysql.host"),
            'port'     => Configuration::get("db.mysql.port"),
            'dbname'   => Configuration::get("db.mysql.database"),
        ]);

        if ('cli' != PHP_SAPI) {
            self::$c['view'] = function ($c) {
                $view = new PhpRenderer("../src/View/");
                $view->setLayout("common/layout/bootstrap.phtml");

                return $view;
            };
        }
    }

    public static function get($name)
    {
        if (is_null(self::$c)) {
            self::init();
        }

        $haystack = self::$c;
        $found = 0;

        $path = explode('.', $name);
        foreach ($path as $idx => $key) {
            if (is_array($haystack) && array_key_exists($key, $haystack)) {
                $haystack = $haystack[ $key ];
                $found = $idx;
            }
        }

        if ($found + 1 == count($path)) {
            if (method_exists($haystack, '__invoke')) {
                return $haystack(self::$c);
            }

            return $haystack;
        }

        return null;
    }

    public static function set($name, $value)
    {
        $path = explode('.', $name);

        $temp = &self::$c;
        foreach ($path as $key) {
            $temp = &$temp[ $key ];
        }
        $temp = $value;
        unset($temp);
    }
}
