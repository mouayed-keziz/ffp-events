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
    'tabs' => [
        'all' => 'All Exhibitors',
        'deleted' => 'Deleted Exhibitors',
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
    ],
    'actions' => [
        "regenerate_password" => [
            "label" => "Regenerate password",
            "modal_heading" => "Regenerate password",
            "modal_description" => "Are you sure you want to regenerate this exhibitor's password? They will receive an email with their new password.",
            "success_title" => "Password regenerated",
            "success_body" => "The exhibitor will receive an email with their new password.",
            "error_title" => "Failed to regenerate password",
            "error_body" => "An error occurred while regenerating the password."
        ]
    ]
];
