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
];
