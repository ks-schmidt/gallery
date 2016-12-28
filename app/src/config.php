<?php

return [
    'settings' => [
        'debug'                  => false,
        'displayErrorDetails'    => false,
        'addContentLengthHeader' => false,

        'logger' => [
            'name' => 'debug',
            'path' => __DIR__ . '/../../logs',
        ],

        'converter' => [
            'source' => [
                'path' => '/var/services/photo',
                'type' => '/.*\.jpe?g$/i',
            ],

            'target' => [
                'path' => '/volume1/web/gallery/app/public/storage',
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
