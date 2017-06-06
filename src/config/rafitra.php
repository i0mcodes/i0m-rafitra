<?php
return [
    'namespaces' => [
        'Api' => base_path() . DIRECTORY_SEPARATOR . 'api',
        'Parts' => base_path() . DIRECTORY_SEPARATOR . 'parts',
    ],
    'protection_middleware' => [
        'auth:api'
    ],
];