<?php

return [
    'resource' => [
        'label' => 'طلب العارض',
        'plural_label' => 'طلبات العارضين',
    ],
    'sections' => [
        'payment_slices' => 'شرائح الدفع',
    ],
    'fields' => [
        'status' => 'الحالة',
        'total_prices' => 'إجمالي الأسعار',
        'answers' => 'إجابات النموذج',
        'created_at' => 'تاريخ التقديم',
        'exhibitor' => 'العارض',
        'event' => 'الحدث',
        'attachments' => 'المرفقات',
        'payment_slice' => [
            'status' => 'الحالة',
            'price' => 'المبلغ',
            'sort' => 'الترتيب',
            'currency' => 'العملة',
            'attachment' => 'إثبات الدفع',
            'due_to' => 'تاريخ الاستحقاق',
        ],
    ],
    'filters' => [
        'status' => 'تصفية حسب الحالة',
        'event' => 'تصفية حسب الحدث',
        'date_range' => 'تصفية حسب النطاق الزمني',
        'payment_slice' => [
            'status' => 'تصفية حسب الحالة',
        ],
    ],
    'actions' => [
        'delete' => 'حذف',
        'view' => 'عرض التفاصيل',
        'update' => 'تحديث',
        'download' => 'تحميل الملف',
        'payment_slice' => [
            'create' => 'إضافة شريحة دفع',
            'create_title' => 'إضافة شريحة دفع للعارض',
        ],
    ],
    'messages' => [
        'deleted' => 'تم حذف الطلب بنجاح',
        'updated' => 'تم تحديث الطلب بنجاح',
    ],
    'empty' => [
        'payment_slices' => 'لا يوجد شرائح دفع',
        'payment_slices_description' => 'يمكنك إضافة شرائح دفع لهذا الطلب.',
    ],
];
