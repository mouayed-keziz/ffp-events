<?php

return [
    'resource' => [
        'single' => 'User',
        'plural' => 'Users',
        'navigation_group' => 'Management',
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
        'title' => 'No users yet',
        'description' => 'Create a user to get started',
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
            'label' => 'Deleted Users',
            'placeholder' => 'Select deleted status',
            'with_trashed' => 'With deleted',
            'only_trashed' => 'Only deleted',
            'without_trashed' => 'Without deleted',
        ],
    ],
];
