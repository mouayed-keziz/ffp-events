<?php

return [
    'title' => 'Platform Settings',
    'description' =>  'Manage your platform settings and configuration.',
    'navigation_label' => 'Platform Settings',
    'navigation_group' => 'Settings',
    'tabs' => [
        'general_information' => 'General Information',
        'social_links' => 'Social Links',
        'faq' => 'FAQ',
        'terms' => 'Terms & Conditions',
        'invoice_details' => 'Invoice Details',
        'jobs' => 'Jobs',
    ],
    'fields' => [
        'name' => [
            'label' => 'Company Name',
            'placeholder' => 'Enter company name'
        ],
        'email' => [
            'label' => 'Email',
            'placeholder' => 'Enter company email'
        ],
        'phone' => [
            'label' => 'Phone',
            'placeholder' => 'Enter company phone'
        ],
        'address' => [
            'label' => 'Address',
            'placeholder' => 'Enter company address'
        ],
        'city' => [
            'label' => 'City',
            'placeholder' => 'Enter city'
        ],
        'state' => [
            'label' => 'State/Region',
            'placeholder' => 'Enter state or region'
        ],
        'country' => [
            'label' => 'Country',
            'placeholder' => 'Enter country'
        ],
        'zip' => [
            'label' => 'Zip Code',
            'placeholder' => 'Enter zip code'
        ],
        'facebookLink' => [
            'label' => 'Facebook Link',
            'placeholder' => 'Enter Facebook page URL'
        ],
        'linkedinLink' => [
            'label' => 'LinkedIn Link',
            'placeholder' => 'Enter LinkedIn profile URL'
        ],
        'instagramLink' => [
            'label' => 'Instagram Link',
            'placeholder' => 'Enter Instagram profile URL'
        ],
        'applicationTerms' => [
            'label' => 'Application Terms & Conditions',
            'placeholder' => 'Enter your application terms and conditions here'
        ],
        'faq' => [
            'question' => [
                'label' => 'Question'
            ],
            'answer' => [
                'label' => 'Answer'
            ]
        ],
        'detailedAddress' => [
            'label' => 'Detailed Address',
            'placeholder' => 'Enter detailed address'
        ],
        'location' => [
            'label' => 'Location',
            'placeholder' => 'Enter location'
        ],
        'capital' => [
            'label' => 'Capital Social',
            'placeholder' => 'Enter capital social'
        ],
        'rc' => [
            'label' => 'RC Number',
            'placeholder' => 'Enter RC number'
        ],
        'nif' => [
            'label' => 'NIF',
            'placeholder' => 'Enter NIF'
        ],
        'ai' => [
            'label' => 'AI',
            'placeholder' => 'Enter AI'
        ],
        'nis' => [
            'label' => 'NIS',
            'placeholder' => 'Enter NIS'
        ],
        'tva' => [
            'label' => 'TVA Percentage',
            'placeholder' => 'Enter TVA percentage'
        ],
        'jobs' => [
            'label' => 'Jobs',
            'ar' => [
                'label' => 'Arabic',
                'placeholder' => 'Enter job title in Arabic'
            ],
            'fr' => [
                'label' => 'French',
                'placeholder' => 'Enter job title in French'
            ],
            'en' => [
                'label' => 'English',
                'placeholder' => 'Enter job title in English'
            ],
            'empty_label' => 'New Job',
            'add_action' => 'Add Job'
        ],
    ],
];
