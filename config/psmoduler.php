<?php
return [
    // this resources has been auto load to layout
    'dist' => [
        'js/main.js',
        'js/main.legacy.js',
        'css/main.css',
    ],
    'routes' => [
        // all routes is active
        'active' => true,
        // section installations
        'installation' => [
            'active' => true,
            'prefix' => '/installation/psmoduler',
            'name_prefix' => 'psmoduler.installation.',
            // this routes has beed except for installation module
            'expect' => [
                'module-assets.assets',
                'psmoduler.installation.index',
                'psmoduler.installation.store',
            ]
        ],
        'creator' => [
            'active' => true,
            'prefix' => '/psmoduler/creator',
            'name_prefix' => 'psmoduler.creator.',
            'middleware' => [
                'web',
                //'auth',
                //'verified'
            ]
        ],
        'example' => [
            'active' => false,
            'prefix' => '/psmoduler/example',
            'name_prefix' => 'psmoduler.example.',
            'middleware' => [
                'web',
                'auth',
                'verified'
            ]
        ],
        'information' => [
            'active' => true,
            'prefix' => '/api/psmoduler/information',
            'name_prefix' => 'api.psmoduler.information.',
            'middleware' => [
                'auth:sanctum',
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Use permissions in application.
    |--------------------------------------------------------------------------
    |
    | This permission has been insert to database with migrations
    | of module permission.
    |
    */
    'permissions' =>[
        'install_packages'
    ],

    /*
    |--------------------------------------------------------------------------
    | Can merge permissions to module permission
    |--------------------------------------------------------------------------
    */
    'merge_permissions' => true,

    'installation' => [
        'auto_redirect' => [
            // user with this permission has been automation redirect to
            // installation package
            'permission' => 'install_packages'
        ]
    ],

    'database' => [
        'tables' => [
            'users' =>'users',
            'psmoduler_histories' =>'psmoduler_histories',
        ]
    ],

];
