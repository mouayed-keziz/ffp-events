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
            'label' => 'العارضون المحذوفون',
            'placeholder' => 'اختر حالة الحذف',
            'with_trashed' => 'مع المحذوف',
            'only_trashed' => 'المحذوف فقط',
            'without_trashed' => 'بدون المحذوف',
        ],
    ],
];
