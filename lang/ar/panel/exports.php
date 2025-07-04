<?php

return [
    'single' => 'تصدير',
    'plural' => 'التصديرات',
    'title' => 'التصديرات',
    'description' => 'عرض وإدارة التصديرات',

    'columns' => [
        'id' => 'المعرف',
        'completed_at' => 'تاريخ الاكتمال',
        'file_name' => 'اسم الملف',
        'exporter' => 'المصدر',
        'processed_rows' => 'الصفوف المعالجة',
        'total_rows' => 'إجمالي الصفوف',
        'successful_rows' => 'الصفوف الناجحة',
        'exported_by' => 'صدر بواسطة',
    ],

    'filters' => [
        'date' => [
            'start' => 'تاريخ البداية',
            'end' => 'تاريخ النهاية',
            'placeholder' => 'اختر تاريخ...',
        ],
        'type' => [
            'label' => 'نوع التصدير',
            'placeholder' => 'اختر نوع التصدير...',
        ],
    ],

    'empty' => [
        'title' => 'لا توجد تصديرات',
        'description' => 'ابدأ بإنشاء تصدير جديد.',
    ],

    'type' => [
        'log' => 'تصدير السجلات',
        'article' => 'تصدير المقالات',
        'event_announcement' => 'تصدير إعلانات الفعاليات',
        'user' => 'تصدير المستخدمين',
        'category' => 'تصدير الفئات',
        'exhibitor' => 'تصدير العارضين',
        'product' => 'تصدير المنتجات',
        'visitor' => 'تصدير الزوار',
        'export' => 'تصدير التصديرات',
        'exhibitor_submission' => 'تصدير طلبات العارضين',
        'visitor_submission' => 'تصدير طلبات الزوار',
    ],
];
