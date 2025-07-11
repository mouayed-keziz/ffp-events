<?php

return [
    'resource' => [
        'single' => 'عارض',
        'plural' => 'العارضين',
    ],
    'columns' => [
        'id' => 'المعرف',
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'created_at' => 'تاريخ الإنشاء',
        'updated_at' => 'تاريخ التحديث',
        'verified_at' => 'تم التحقق',
        'roles' => 'الأدوار',
    ],
    'form' => [
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
    ],
    'empty_states' => [
        'title' => 'لا يوجد عارضين',
        'description' => 'قم بإنشاء عارض للبدء',
        'name' => 'لم يتم توفير اسم',
        'email' => 'لم يتم توفير بريد إلكتروني',
        'roles' => 'لم يتم تعيين أي دور',
    ],
    'tabs' => [
        'all' => 'كل العارضين',
        'deleted' => 'العارضين المحذوفين',
    ],
    'filters' => [
        'roles' => [
            'label' => 'الأدوار',
            'placeholder' => 'اختر الأدوار',
        ],
        'verification' => [
            'label' => 'حالة التحقق',
            'placeholder' => 'اختر الحالة',
            'verified' => 'تم التحقق',
            'unverified' => 'غير متحقق',
        ],
    ],
    'actions' => [
        "regenerate_password" => [
            "label" => "إعادة تعيين كلمة المرور",
            "modal_heading" => "إعادة تعيين كلمة المرور",
            "modal_description" => "هل أنت متأكد من رغبتك في إعادة تعيين كلمة مرور هذا العارض؟ سيتلقى بريداً إلكترونياً يحتوي على كلمة المرور الجديدة.",
            "success_title" => "تم إعادة تعيين كلمة المرور",
            "success_body" => "سيتلقى العارض بريداً إلكترونياً يحتوي على كلمة المرور الجديدة.",
            "error_title" => "فشل في إعادة تعيين كلمة المرور",
            "error_body" => "حدث خطأ أثناء إعادة تعيين كلمة المرور."
        ]
    ]
];
