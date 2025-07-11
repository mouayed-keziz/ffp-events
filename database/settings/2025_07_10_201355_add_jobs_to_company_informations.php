<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('default.jobs', [
            [
                'ar' => 'الرئيس التنفيذي',
                'fr' => 'CEO',
                'en' => 'CEO'
            ],
            [
                'ar' => 'المدير العام',
                'fr' => 'Directeur Général (DG)',
                'en' => 'General Manager'
            ],
            [
                'ar' => 'الرئيس المدير العام',
                'fr' => 'Président Directeur Général (PDG)',
                'en' => 'Chairman and CEO'
            ],
            [
                'ar' => 'مسير',
                'fr' => 'Gérant',
                'en' => 'Manager'
            ],
            [
                'ar' => 'مسير مشارك',
                'fr' => 'Co-gérant',
                'en' => 'Co-Manager'
            ],
            [
                'ar' => 'مؤسس',
                'fr' => 'Fondateur',
                'en' => 'Founder'
            ],
            [
                'ar' => 'مؤسس مشارك',
                'fr' => 'Co-Fondateur',
                'en' => 'Co-Founder'
            ],
            [
                'ar' => 'شريك',
                'fr' => 'Associé',
                'en' => 'Partner'
            ],
            [
                'ar' => 'مهندس معماري',
                'fr' => 'Architecte',
                'en' => 'Architect'
            ],
            [
                'ar' => 'نجار',
                'fr' => 'Menuisier',
                'en' => 'Carpenter'
            ],
            [
                'ar' => 'تاجر',
                'fr' => 'Commerçant',
                'en' => 'Merchant'
            ],
            [
                'ar' => 'مهندس',
                'fr' => 'Ingénieur',
                'en' => 'Engineer'
            ],
            [
                'ar' => 'رائد أعمال',
                'fr' => 'Entrepreneur',
                'en' => 'Entrepreneur'
            ],
            [
                'ar' => 'مدير التصدير',
                'fr' => 'Directeur Export / Export Manager',
                'en' => 'Export Director / Export Manager'
            ],
            [
                'ar' => 'رئيس قسم الوصفات الطبية',
                'fr' => 'Chef de département Prescription',
                'en' => 'Prescription Department Head'
            ],
            [
                'ar' => 'مدير الدراسات',
                'fr' => 'Directeur des Études',
                'en' => 'Studies Director'
            ],
            [
                'ar' => 'المدير التقني',
                'fr' => 'Directeur Technique',
                'en' => 'Technical Director'
            ],
            [
                'ar' => 'المدير التجاري',
                'fr' => 'Directeur Commercial',
                'en' => 'Commercial Director'
            ],
            [
                'ar' => 'مدير التسويق والاتصال',
                'fr' => 'Directeur Marketing et Communication',
                'en' => 'Marketing and Communication Director'
            ],
            [
                'ar' => 'مدير التطوير',
                'fr' => 'Directeur Développement',
                'en' => 'Development Director'
            ],
            [
                'ar' => 'مدير العمليات',
                'fr' => 'Directeur des Opérations',
                'en' => 'Operations Director'
            ],
            [
                'ar' => 'المدير المالي',
                'fr' => 'Directeur Financier',
                'en' => 'Financial Director'
            ],
            [
                'ar' => 'المسؤول التقني',
                'fr' => 'Responsable Technique',
                'en' => 'Technical Manager'
            ],
            [
                'ar' => 'المسؤول التجاري',
                'fr' => 'Responsable Commercial',
                'en' => 'Commercial Manager'
            ],
            [
                'ar' => 'مسؤول التسويق والاتصال',
                'fr' => 'Responsable Marketing et Communication',
                'en' => 'Marketing and Communication Manager'
            ],
            [
                'ar' => 'مسؤول التطوير',
                'fr' => 'Responsable Développement',
                'en' => 'Development Manager'
            ],
            [
                'ar' => 'مسؤول العمليات',
                'fr' => 'Responsable des Opérations',
                'en' => 'Operations Manager'
            ],
            [
                'ar' => 'المسؤول المالي',
                'fr' => 'Responsable Financier',
                'en' => 'Financial Manager'
            ],
            [
                'ar' => 'مسؤول المشتريات',
                'fr' => 'Responsable des Achats',
                'en' => 'Purchasing Manager'
            ],
            [
                'ar' => 'مندوب تجاري',
                'fr' => 'Commercial / Délégué',
                'en' => 'Sales Representative'
            ],
            [
                'ar' => 'مندوب تجاري تقني',
                'fr' => 'Technico-commercial',
                'en' => 'Technical Sales Representative'
            ],
            [
                'ar' => 'مكلف بالتسويق والاتصال',
                'fr' => 'Chargé Marketing et Communication',
                'en' => 'Marketing and Communication Officer'
            ],
            [
                'ar' => 'مكلف بالتطوير',
                'fr' => 'Chargé du Développement',
                'en' => 'Development Officer'
            ],
            [
                'ar' => 'مكلف بالعمليات',
                'fr' => 'Chargé des Opérations',
                'en' => 'Operations Officer'
            ],
            [
                'ar' => 'مكلف بالمشتريات',
                'fr' => 'Chargé des Achats',
                'en' => 'Purchasing Officer'
            ],
            [
                'ar' => 'طالب',
                'fr' => 'Étudiant',
                'en' => 'Student'
            ],
            [
                'ar' => 'موظف حكومي',
                'fr' => 'Fonctionnaire',
                'en' => 'Civil Servant'
            ],
            [
                'ar' => 'مستشار',
                'fr' => 'Consultant',
                'en' => 'Consultant'
            ]
        ]);
    }
};
