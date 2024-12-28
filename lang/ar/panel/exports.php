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
    ],

    'empty' => [
        'title' => 'لا توجد تصديرات',
        'description' => 'ابدأ بإنشاء تصدير جديد.',
    ],
];
