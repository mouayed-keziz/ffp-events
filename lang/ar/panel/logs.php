<?php

return [
    'events' => [
        'creation' => 'إنشاء',
        'modification' => 'تعديل',
        'deletion' => 'حذف',
        'force_deletion' => 'حذف نهائي',
        'restoration' => 'استعادة',
        'login' => 'تسجيل الدخول',
        'logout' => 'تسجيل الخروج',
    ],
    'resource' => [
        'single' => 'سجل',
        'plural' => 'سجلات',
    ],
    "names" => [
        'categories' => 'الفئات',
        'articles' => 'المقالات',
        'authentication' => 'المصادقة',
        'event_announcements' => 'إعلانات الأحداث',
        'products' => 'المنتجات',
        'plans' => 'الخطط',
        'plan_tiers' => 'مستويات الخطة',
        'visitor_submissions' => 'تقديمات الزوار',
        'exhibitor_submissions' => 'تقديمات العارضين',
        'company_information' => 'معلومات الشركة',
        'banners' => 'البنرات',
    ],
    'columns' => [
        "log_name" => "اسم السجل",
        "subject" => "الموضوع",
        "causer" => "المتسبب",
        "event" => "الحدث",
        "created_at" => "تاريخ الإنشاء",
    ],
    'empty_states' => [
        'log_name' => 'لم يتم تحديد السجل',
        'subject' => 'بدون موضوع',
        'causer' => 'بدون متسبب',
        'event' => 'بدون حدث',
        "created_at" => "بدون تاريخ إنشاء",
        'deleted_record' => '*سجل محذوف*',
    ],
    'filters' => [
        'date' => [
            'from' => 'تاريخ البداية',
            'until' => 'تاريخ النهاية',
            'empty_state' => [
                'from' => 'بدون تاريخ بداية',
                'until' => 'بدون تاريخ نهاية'
            ]
        ],
        'log_name' => 'نوع السجل',
        'event' => 'نوع الحدث'
    ],
    'actions' => [
        'export' => [
            "label" =>  "تصدير السجلات",
        ],
        'delete_all' => [
            'label' => 'حذف جميع السجلات',
            'modal' => [
                'heading' => 'حذف جميع السجلات',
                'description' => 'هل أنت متأكد من حذف جميع السجلات؟ لا يمكن التراجع عن هذا الإجراء.',
                'submit_label' => 'نعم، احذف جميع السجلات',
                'password' => [
                    'label' => 'كلمة المرور',
                    'helper_text' => 'الرجاء إدخال كلمة المرور لتأكيد هذا الإجراء'
                ]
            ],
            'notifications' => [
                'success' => [
                    'title' => 'تم حذف السجلات بنجاح',
                    'body' => 'تم حذف جميع السجلات بشكل نهائي.'
                ],
                'error' => [
                    'title' => 'كلمة المرور غير صحيحة',
                    'body' => 'كلمة المرور التي أدخلتها غير صحيحة.'
                ]
            ]
        ]
    ],

    "export" => []
];
