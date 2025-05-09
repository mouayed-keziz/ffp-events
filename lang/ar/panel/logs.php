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
        'register' => 'تسجيل حساب',

        // Visitor events
        'visitor_submitted' => 'تسجيل الزائر',

        // Exhibitor events
        'exhibitor_submitted' => 'تسجيل العارض',
        'exhibitor_uploaded_payment_proof' => 'تحميل إثبات الدفع',
        'exhibitor_submitted_post_forms' => 'تقديم نموذج ما بعد الدفع',
        'exhibitor_requested_update' => 'طلب تحديث',
        'exhibitor_updated_submission' => 'تحديث التقديم',
        'exhibitor_downloaded_invoice' => 'تنزيل الفاتورة',
        'exhibitor_updated_badges' => 'تحديث الشارات',

        // Admin actions on exhibitor submissions
        'exhibitor_submission_accepted' => 'قبول التقديم',
        'exhibitor_submission_rejected' => 'رفض التقديم',
        'exhibitor_payment_validated' => 'تأكيد الدفع',
        'exhibitor_payment_rejected' => 'رفض الدفع',
        'exhibitor_payment_slice_deleted' => 'حذف شريحة الدفع',
        'exhibitor_submission_marked_ready' => 'تمييز كجاهز',
        'exhibitor_submission_archived' => 'أرشفة التقديم',
        'exhibitor_update_request_approved' => 'الموافقة على طلب التحديث',
        'exhibitor_update_request_denied' => 'رفض طلب التحديث',
    ],
    'exhibitor_submissions' => [
        'create' => 'تقديم النموذج',
        'update' => 'تحديث النموذج',
        'payment_proof' => 'تقديم إثبات الدفع',
        'post_form' => 'تقديم نموذج ما بعد الدفع',
        'old_data' => 'البيانات السابقة',
        'new_data' => 'البيانات الجديدة',
    ],
    'visitor_submissions' => [
        'create' => 'تقديم التسجيل',
        'update' => 'تحديث التسجيل',
        'attendance' => 'تسجيل الحضور',
        'old_data' => 'البيانات السابقة',
        'new_data' => 'البيانات الجديدة',
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
        'plan_tiers' => 'خطط الرعاية',
        'visitor_submissions' => 'تسجيلات الزوار',
        'exhibitor_submissions' => 'تسجيلات العارضين',
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
