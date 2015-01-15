<?php

return [

    'admin_uri' => 'admin',

    'id' => ['username', 'email'],

    // The Super Admin role
    // (returns true for all permissions)
    'super_admin' => 'super_admin',

    // DB prefix for tables
    'prefix' => '',

    // Define Models if you extend them.
    'models' => [
        'user' => 'User',
        'group' => 'Group',
        'privilege' => 'Privilege',
    ]

];