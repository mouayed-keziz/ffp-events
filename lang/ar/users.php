<?php

return [
    'resource' => [
        'single' => 'مستخدم',
        'plural' => 'المستخدمين',
        'navigation_group' => 'الإدارة',
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
        'title' => 'لا يوجد مستخدمين',
        'description' => 'قم بإنشاء مستخدم للبدء',
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
            'label' => 'المستخدمون المحذوفون',
            'placeholder' => 'اختر حالة الحذف',
            'with_trashed' => 'مع المحذوف',
            'only_trashed' => 'المحذوف فقط',
            'without_trashed' => 'بدون المحذوف',
        ],
    ],
];
