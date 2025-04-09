<?php

return [
    'resource' => [
        'single' => 'بانر',
        'plural' => 'بانرات',
    ],
    'form' => [
        'sections' => [
            'general' => 'معلومات عامة',
            'image' => 'صورة البانر',
        ],
        'fields' => [
            'title' => 'العنوان',
            'url' => 'الرابط',
            'order' => 'ترتيب العرض',
            'is_active' => 'نشط',
            'image' => 'صورة البانر',
        ],
    ],
    'table' => [
        'columns' => [
            'image' => 'الصورة',
            'title' => 'العنوان',
            'url' => 'الرابط',
            'order' => 'الترتيب',
            'is_active' => 'نشط',
            'created_at' => 'تاريخ الإنشاء',
            'updated_at' => 'تاريخ التحديث',
        ],
        'actions' => [
            'preview' => 'معاينة الرابط',
        ],
    ],
    'notifications' => [
        'created' => [
            'title' => 'تم إنشاء البانر',
            'body' => 'تم إنشاء البانر بنجاح.',
        ],
        'updated' => [
            'title' => 'تم تحديث البانر',
            'body' => 'تم تحديث البانر بنجاح.',
        ],
        'deleted' => [
            'title' => 'تم حذف البانر',
            'body' => 'تم حذف البانر بنجاح.',
        ],
    ],
];
