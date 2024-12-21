<?php

return [
    'resource' => [
        'single' => 'Exhibitor',
        'plural' => 'Exhibitors',
    ],
    'columns' => [
        'id' => 'ID',
        'name' => 'Name',
        'email' => 'Email',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'verified_at' => 'Verified',
        'roles' => 'Roles',
    ],
    'form' => [
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
    ],
    'empty_states' => [
        'title' => 'No exhibitors yet',
        'description' => 'Create an exhibitor to get started',
        'name' => 'No name provided',
        'email' => 'No email provided',
        'roles' => 'No roles assigned',
    ],
    'filters' => [
        'roles' => [
            'label' => 'Roles',
            'placeholder' => 'Select roles',
        ],
        'verification' => [
            'label' => 'Verification Status',
            'placeholder' => 'Select status',
            'verified' => 'Verified',
            'unverified' => 'Unverified',
        ],
        'trashed' => [
            'label' => 'Deleted Exhibitors',
            'placeholder' => 'Select deleted status',
            'with_trashed' => 'With deleted',
            'only_trashed' => 'Only deleted',
            'without_trashed' => 'Without deleted',
        ],
    ],
];
