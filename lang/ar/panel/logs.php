<?php

return [
    'events' => [
        'creation' => 'إنشاء',
        'modification' => 'تعديل',
        'deletion' => 'حذف',
    ],
    'resource' => [
        'single' => 'سجل',
        'plural' => 'سجلات',
    ],
    "names" => [
        'categories' => 'التصنيفات',
        'articles' => 'المقالات',
    ],
    'columns' => [
        "log_name" => "اسم السجل",
        "subject" => "الموضوع",
        "causer" => "المتسبب",
        "event" => "الحدث",
        "created_at" => "تاريخ الإنشاء",
    ],
    'empty_states' => [
        'log_name' => 'لم يتم تحديد السجل',
        'subject' => 'بدون موضوع',
        'causer' => 'بدون متسبب',
        'event' => 'بدون حدث',
        "created_at" => "بدون تاريخ إنشاء",
    ],
    'filters' => [
        'date' => [
            'from' => 'تاريخ البداية',
            'until' => 'تاريخ النهاية',
            'empty_state' => [
                'from' => 'بدون تاريخ بداية',
                'until' => 'بدون تاريخ نهاية'
            ]
        ],
        'log_name' => 'نوع السجل',
        'event' => 'نوع الحدث'
    ]
];
