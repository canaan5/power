<?php

return [

    'id' => ['username', 'email'],

    // The Super Admin role
    // (returns true for all permissions)
    'super_admin' => 'Oga4Top',

    // DB prefix for tables
    'prefix' => '',

    // Define Models if you extend them.
    'models' => [
        'user' => 'User',
        'group' => 'Group',
        'permission' => 'Permission',
    ]

];