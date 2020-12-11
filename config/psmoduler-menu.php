<?php
return [
    'merge_to_navigation' => true,
    'navs' => [
        'sidebar' => [
            [
                'name' => 'Psmoduler',
                'link' => '/psmoduler/creator',
                'icon' => 'speed',
                'key' => 'psmoduler::menus.psmoduler',
                'children_top' => [
                    [
                        'name' => 'Creator',
                        'link' => '/psmoduler/creator',
                        'key' => 'psmoduler::menus.creator',
                    ],
                    [
                        'name' => 'Example',
                        'link' => '/psmoduler/example',
                        'key' => 'psmoduler::menus.example',
                    ]
                ],
                'children' => [
                    [
                        'name' => 'Creator',
                        'link' => '/psmoduler/creator',
                        'key' => 'psmoduler::menus.creator',
                    ],
                    [
                        'name' => 'Example',
                        'link' => '/psmoduler/example',
                        'key' => 'psmoduler::menus.example',
                    ]
                ],
            ]
        ],
        'guestSidebar' => [
            [
                'name' => 'Psmoduler',
                'link' => '/psmoduler/creator',
                'icon' => 'speed',
                'key' => 'psmoduler::menus.psmoduler',
                'children_top' => [
                    [
                        'name' => 'Creator',
                        'link' => '/psmoduler/creator',
                        'key' => 'psmoduler::menus.creator',
                    ],
                ],
                'children' => [
                    [
                        'name' => 'Creator',
                        'link' => '/psmoduler/creator',
                        'key' => 'psmoduler::menus.creator',
                    ],
                ],
            ]
        ],
    ]
];
