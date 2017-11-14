<?php
return [
    'namespaces' => [
        'Components' => base_path() . DIRECTORY_SEPARATOR . 'Components',
        'Api' => base_path() . DIRECTORY_SEPARATOR . 'api',
    ],
    'protection_middleware' => [
        'auth:api'
    ],
];