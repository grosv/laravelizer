<?php

return [
    'model' => [
        'suppress' => env('LARAVELIZER_MODEL_SUPPRESS', true),
        'path' => env('LARAVELIZER_MODEL_PATH', app_path()),
        'stub' => env('LARAVELIZER_MODEL_STUB', 'laravelizer::model'),
    ],
    'migration' => [
        'suppress' => env('LARAVELIZER_MIGRATION_SUPPRESS', true),
        'path' => env('LARAVELIZER_MIGRATION_PATH', database_path('migrations')),
        'stub' => env('LARAVELIZER_MIGRATION_STUB', 'laravelizer::migration'),
    ],
    'factory' => [
        'suppress' => env('LARAVELIZER_FACTORY_SUPPRESS', false),
        'path' => env('LARAVELIZER_FACTORY_PATH', database_path('factories')),
        'stub' => env('LARAVELIZER_FACTORY_STUB', 'laravelizer::factory'),
    ],
    'nova' => [
        'suppress' => env('LARAVELIZER_NOVA_SUPPRESS', true),
        'path' => env('LARAVELIZER_NOVA_PATH', app_path('Nova')),
        'stub' => env('LARAVELIZER_NOVA_STUB', 'laravelizer::nova'),
    ],
    'test' => [
        'suppress' => env('LARAVELIZER_TEST_SUPPRESS', true),
        'path' => env('LARAVELIZER_TEST_PATH', base_path('tests/unit')),
        'stub' => env('LARAVELIZER_TEST_STUB', 'laravelizer::test'),
    ]
];