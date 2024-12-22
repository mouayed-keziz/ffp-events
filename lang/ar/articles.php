<?php

return [
    'resource' => [
        'single' => 'مقال',
        'plural' => 'مقالات',
    ],
    'columns' => [
        'id' => 'المعرف',
        'title' => 'العنوان',
        'description' => 'الوصف',
        'content' => 'المحتوى',
        'created_at' => 'تاريخ الإنشاء',
        'updated_at' => 'تاريخ التحديث',
        'deleted_at' => 'تاريخ الحذف',
    ],
    'form' => [
        'title' => 'العنوان',
        'description' => 'الوصف',
        'content' => 'المحتوى',
        "published_date" => "تاريخ النشر",
    ],
    'empty_states' => [
        'title' => "بدون عنوان",
        'description' => "بدون وصف",
        'contenu' => "بدون محتوى",
        'deleted_at' => "غير محذوف",
        'published_at' => "غير منشور",
        "created_at" => "غير منشئ",
        "updated_at" => "غير محدث",
    ],
    'placeholders' => [
        'title' => 'أدخل العنوان',
        'description' => 'أدخل الوصف',
        'content' => 'أدخل المحتوى',
    ],
    'status' => [
        "all" => "الكل",
        "draft" => "مسودة",
        "pending" => "قيد الانتظار",
        "published" => "منشور",
        "deleted" => "محذوف",
    ],
    'actions' => []
];
