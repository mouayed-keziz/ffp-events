<?php

return [
    'resource' => [
        'label' => 'حدثي',
        'plural_label' => 'أحداثي',
    ],

    'navigation' => [
        'group' => 'أحداثي',
    ],

    'fields' => [
        'title' => 'العنوان',
        'description' => 'الوصف',
        'location' => 'الموقع',
        'start_date' => 'تاريخ البداية',
        'end_date' => 'تاريخ النهاية',
        'visitor_registration_start_date' => 'بداية تسجيل الزوار',
        'visitor_registration_end_date' => 'نهاية تسجيل الزوار',
        'exhibitor_registration_start_date' => 'بداية تسجيل العارضين',
        'exhibitor_registration_end_date' => 'نهاية تسجيل العارضين',
        'website_url' => 'رابط الموقع الإلكتروني',
        'contact' => 'التواصل',
        'currencies' => 'العملات',
        'image' => 'صورة الحدث',
        'terms' => 'الشروط والأحكام',
        'content' => 'المحتوى',
    ],

    'actions' => [
        'view' => 'عرض الحدث',
        'edit' => 'تعديل الحدث',
    ],

    'infolist' => [
        'sections' => [
            'basic_info' => 'المعلومات الأساسية',
            'dates' => 'تواريخ الحدث',
            'registration_dates' => 'فترات التسجيل',
            'additional_info' => 'معلومات إضافية',
        ],
    ],

    'table' => [
        'columns' => [
            'title' => 'العنوان',
            'location' => 'الموقع',
            'start_date' => 'تاريخ البداية',
            'end_date' => 'تاريخ النهاية',
            'status' => 'الحالة',
        ],
        'filters' => [
            'status' => 'الحالة',
            'date_range' => 'نطاق التاريخ',
        ],
    ],

    'relation_managers' => [
        'current_attendees' => [
            'title' => 'الحضور الحاليون',
            'columns' => [
                'badge_name' => 'الاسم',
                'badge_email' => 'البريد الإلكتروني',
                'badge_company' => 'الشركة',
                'badge_position' => 'المنصب',
                'checked_in_at' => 'وقت تسجيل الدخول',
                'checked_in_by_user' => 'سجل الدخول بواسطة',
            ],
            'filters' => [
                'checked_in_date' => 'تاريخ تسجيل الدخول',
                'company' => 'الشركة',
                'position' => 'المنصب',
            ],
        ],
        'badge_check_logs' => [
            'title' => 'سجلات فحص الشارات',
            'columns' => [
                'badge_name' => 'الاسم',
                'badge_email' => 'البريد الإلكتروني',
                'badge_company' => 'الشركة',
                'action' => 'الإجراء',
                'action_time' => 'وقت الإجراء',
                'checked_by_user' => 'تم الفحص بواسطة',
                'note' => 'ملاحظة',
            ],
            'filters' => [
                'action' => 'الإجراء',
                'action_date' => 'تاريخ الإجراء',
                'company' => 'الشركة',
            ],
        ],
    ],

    'status' => [
        'upcoming' => 'قادم',
        'ongoing' => 'جاري',
        'past' => 'منتهي',
    ],
];
