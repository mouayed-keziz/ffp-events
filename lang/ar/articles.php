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
        'slug' => 'المسار',
        "status" => "الحالة",
        'image' => 'الصورة',
    ],
    'form' => [
        'title' => 'العنوان',
        'description' => 'الوصف',
        'content' => 'المحتوى',
        "published_date" => "تاريخ النشر",
        'slug' => 'المسار',
        'tabs' => [
            'information' => 'المعلومات',
            'content' => 'المحتوى',
        ],
        'sections' => [
            'featured_image' => 'الصورة المميزة',
        ],
    ],
    'empty_states' => [
        'title' => "بدون عنوان",
        'description' => "بدون وصف",
        'contenu' => "بدون محتوى",
        'deleted_at' => "غير محذوف",
        'published_at' => "غير منشور",
        "created_at" => "غير منشئ",
        "updated_at" => "غير محدث",
        'slug' => "بدون مسار",
        'image' =>  'بدون صورة',
    ],
    'placeholders' => [
        'title' => 'أدخل العنوان',
        'description' => 'أدخل الوصف',
        'content' => 'أدخل المحتوى',
        'slug' => 'أدخل المسار',
    ],
    'status' => [
        "all" => "الكل",
        "draft" => "مسودة",
        "pending" => "قيد الانتظار",
        "published" => "منشور",
        "deleted" => "محذوف",
    ],
    'actions' => [
        "visit" =>  "زيارة",
    ],
    "categories" => [
        "single" => "تصنيف",
        "plural" => "تصنيفات",
        "articles" => "المقالات",
        "fields" => [
            "name" => "الاسم",
            "slug" => "المسار",
        ],
        "goto_article" =>  "الذهاب إلى المقال",
    ]
];
