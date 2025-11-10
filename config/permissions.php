<?php
// config/permissions.php
return [
    'actions' => [
        'getAll',
        'getOne',
        'create',
        'update',
        'delete',
    ],
    // Optionnel : mapping route → action
    'route_action_map' => [
        'index'   => 'getAll',
        'show'    => 'getOne',
        'create'  => 'create',
        'store'   => 'create',
        'edit'    => 'update',
        'update'  => 'update',
        'destroy' => 'delete',
    ],
];
