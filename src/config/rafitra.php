<?php
return [
    'namespaces' => [
        'Components' => base_path() . DIRECTORY_SEPARATOR . 'Components',
        'Parts' => base_path() . DIRECTORY_SEPARATOR . 'parts',
    ],
    'protection_middleware' => [
        'auth:api'
    ],
];