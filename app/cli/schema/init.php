<?php

use Gallery\App\Configuration;
use \Doctrine\DBAL\DriverManager;

if ('cli' == PHP_SAPI) {

    require __DIR__ . '/../../vendor/autoload.php';

    $dotenv = new Dotenv\Dotenv(__DIR__ . "/../../");
    $dotenv->overload();

    $config = require __DIR__ . '/../../src/config.php';

    $params = $config['db']['mysql'];
    $connection = DriverManager::getConnection([
        'driver' => 'pdo_mysql',

        'user'     => $params["user"],
        'password' => $params["password"],
        'host'     => $params["host"],
        'port'     => $params["port"],
    ]);

    $error = false;
    $connection->beginTransaction();
        echo sprintf("INIT DATABASE%s", PHP_EOL);
        $sqlList = array_filter(explode(';', file_get_contents(__DIR__ . '/database.sql')));
        foreach ($sqlList as $sql) {
            $sql = trim(preg_replace('/[ ]+/', ' ', preg_replace('/\s/', ' ', $sql)));
            if (empty($sql)) {
                continue;
            }

            try {
                echo sprintf("- %.76s ...%s", $sql, PHP_EOL);
                $connection->exec($sql);
            } catch (\Exception $e) {
                $error = true;
                echo sprintf("- \e[31mFAILED\e[0m%s", PHP_EOL);
                break;
            }
        }
    if ($error) {
        echo sprintf("ROLLBACK%s", PHP_EOL);
        $connection->rollBack();
    }
    else {
        echo sprintf("COMMIT%s", PHP_EOL);
        $connection->commit();
    }
}
