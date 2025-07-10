<?php

return [
    'resource' => [
        'single' => 'مستخدم FFP',
        'plural' => 'مستخدمين FFP',
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
        'roles' => 'الأدوار',
        'assigned_events' => 'الأحداث المُعيَّنة',
    ],
    'empty_states' => [
        'title' => 'لا يوجد مستخدمين FFP',
        'description' => 'قم بإنشاء مستخدم FFP للبدء',
        'name' => 'لم يتم توفير اسم',
        'email' => 'لم يتم توفير بريد إلكتروني',
        'roles' => 'لم يتم تعيين أي دور',
        'assigned_events' => 'لم يتم تعيين أي حدث',
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
        'trashed' => [
            'label' => 'مستخدمين FFP المحذوفون',
            'placeholder' => 'اختر حالة الحذف',
            'with_trashed' => 'مع المحذوف',
            'only_trashed' => 'المحذوف فقط',
            'without_trashed' => 'بدون المحذوف',
        ],
    ],
    'actions' => [
        "regenerate_password" => [
            "label" => "إعادة تعيين كلمة المرور",
            "modal_heading" => "إعادة تعيين كلمة المرور",
            "modal_description" => "هل أنت متأكد من رغبتك في إعادة تعيين كلمة مرور هذا المستخدم FFP؟ سيتلقى بريداً إلكترونياً يحتوي على كلمة المرور الجديدة.",
            "success_title" => "تم إعادة تعيين كلمة المرور",
            "success_body" => "سيتلقى المستخدم FFP بريداً إلكترونياً يحتوي على كلمة المرور الجديدة.",
            "error_title" => "فشل في إعادة تعيين كلمة المرور",
            "error_body" => "حدث خطأ أثناء إعادة تعيين كلمة المرور."
        ]
    ],
    'tabs' => [
        'all' => 'جميع مستخدمين FFP',
        'admin' => 'المدراء',
        'super_admin' => 'المدراء الأعلى',
        'hostess' => 'المضيفات',
        'deleted' => 'المحذوفين'
    ]
];
