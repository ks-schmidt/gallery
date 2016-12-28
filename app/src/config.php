<?php

return [
    'settings' => [
        'debug'                  => true,
        'displayErrorDetails'    => true,
        'addContentLengthHeader' => false,

        'logger' => [
            'name' => 'debug',
            'path' => __DIR__ . '/../../logs',
        ],

        'converter' => [
            'source' => [
                'path' => '/server/http/source',
                'type' => '/.*\.jpe?g$/i',
            ],

            'target' => [
                'path' => '/server/http/app/public/storage',
                'type' => [
                    'download' => '/.*.dl\.jpe?g$/i',
                    'preview'  => '/.*.pv\.jpe?g$/i',
                    'thumb'    => '/.*.tn\.jpe?g$/i',
                ],
            ],
        ],
    ],

    'db' => [
        'mysql' => [
            'host'     => getenv('MYSQL_HOST'),
            'user'     => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASSWORD'),
            'port'     => getenv('MYSQL_PORT'),
            'database' => getenv('MYSQL_DATABASE'),
        ],
    ],
];
