<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedEventAnnouncementsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('event_announcements')->delete();
        
        \DB::table('event_announcements')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => '{"fr":"DZ BUILD","en":"DZ BUILD","ar":"DZ BUILD"}',
                'description' => '{"fr":"Salon de l’innovation du batiment et des materiaux de construction","en":"Salon de l’innovation du batiment et des materiaux de construction","ar":"Salon de l’innovation du batiment et des materiaux de construction"}',
                'terms' => '{"fr":"<h2>Conditions d\'utilisation du DZ Build :</h2><ul><li>La participation au DZ Build est libre et gratuite pour les professionnels de l\'industrie.</li><li>Les exposants sont responsables de la qualité et de la sécurité de leurs produits et services présentés sur le salon.</li><li>Tout accès non autorisé à la zone d\'exposition ou aux espaces réservés sera considéré comme une violation des conditions d\'utilisation.</li><li>DZ Build ne sera pas responsable en cas de dommages ou pertes directs ou indirects résultant de l\'utilisation du site web.</li></ul>","en":"<h2>Terms of Use for DZ Build :</h2><ul><li>Participation in DZ Build is free and open to industry professionals.</li><li>Exhibitors are responsible for the quality and safety of their products and services presented on the expo floor.</li><li>Any unauthorized access to the exhibition area or reserved spaces will be considered a breach of these terms of use.</li><li>DZ Build will not be held liable for direct or indirect damages or losses resulting from the use of this website.</li></ul><p><br></p>","ar":"<h2 dir=\\"rtl\\">&nbsp;شروط استخدام DZ Build :</h2><ul dir=\\"rtl\\"><li>المشاركة في DZ Build مجانية و خالية من التكلفة للمهنيين والموظفين.</li><li>البائعين مسئولون عن جودة وموثوقية المنتجات والخدمات المعروضة على الندوة.</li><li>أي دخول غير مصرح به إلى منطقة العرض أو المساحات المحجوزة ستعامل كأمر مخالف للشروط المستفادة.</li><li>&nbsp;لن تكون مسؤولة في حال حدوث أضرار أو خسائر مباشرة أو غير مباشرة نتيجة لاستخدام الموقع الإلكتروني.</li></ul><p><br></p>"}',
                'start_date' => '2025-02-15 18:49:32',
                'end_date' => '2025-03-05 18:49:34',
                'location' => 'Praesent nulla massa, hendrerit vestibulum gravida in, feugiat auctor felis.',
                'visitor_registration_start_date' => '2025-02-16 18:49:41',
                'visitor_registration_end_date' => '2025-03-06 18:49:44',
                'exhibitor_registration_start_date' => '2025-02-22 18:49:46',
                'exhibitor_registration_end_date' => '2025-02-28 18:49:51',
                'website_url' => 'https://dz-build.com',
                'contact' => '{"name":"Amir Rabhi","email":"amir.rabhi@ffp-events.com","phone_number":"0547 87 43 23"}',
                'content' => '{"fr":"<h2><strong>Le DZ Build</strong></h2><ul><li>est un événement</li><li>&nbsp;sans précédent&nbsp;</li><li>qui réunit&nbsp;</li></ul><p>les professionnels de l\'industrie du bâtiment et des<br>matériaux de construction pour discuter de la dernière innovation. Le Salon de l\'innovation du batiment et des<br>materiaux de construction est une plateforme unique permettant aux exposants d\'exposer leurs dernières<br>technologies et solutions, tandis que les visiteurs peuvent découvrir les derniers développements dans le domaine.<br>Au cours de cette journée exceptionnelle, nous vous présenterons les dernières innovations qui transformeront la<br>face du bâtiment et des matériaux de construction pour un avenir plus durable et plus respectueux de<br>l\'environnement.</p>","en":"<h2>DZ Build</h2><p>is an unprecedented event that brings together industry professionals in the building and construction<br>materials sector to discuss the latest innovations. The Building Innovation and Construction Materials Expo is a<br>unique platform allowing exhibitors to showcase their latest technologies and solutions, while visitors can<br>discover the latest developments in the field. Throughout this exceptional day, we will present the latest<br>innovations that are set to transform the face of building and construction for a more sustainable and<br>environmentally-friendly future.</p>","ar":"<h2>&nbsp;DZ Build</h2><p dir=\\"rtl\\">هو حدث غير مسبوق يجمع المهندسين والمهنيين في صناعة البناء والكميات الحاصلة على الهدنة من أجل التحدث عن<br>أحدث المساهمة. معرض لتكنولوجيا البناء والتجديد هو منصة فريدة تسمح للناشرين لإظهار أحدث تقنياتهم والتقانات المتطورة<br>، في حين يمكن للمتفرجين على المشاركين في المعرض اكتشاف أحدث الأبحاث والتطورات في مجال التكنولوجيا. خلال ذلك اليوم<br>التاريخي، سنقدم لكم أحدث المساهمة التي ستواجه واجهة البناء والكميات الحاصلة على الهدنة من أجل مستقبل أطول وسبحان.</p>"}',
                'currencies' => '["DZD","EUR","USD"]',
                'created_at' => '2025-02-15 17:50:06',
                'updated_at' => '2025-02-26 18:53:59',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'title' => '{"fr":"a","en":"b","ar":"c"}',
                'description' => '{"fr":"d","en":"e","ar":"f"}',
                'terms' => NULL,
                'start_date' => '2025-02-01 21:40:36',
                'end_date' => '2025-03-01 21:40:38',
                'location' => 'Praesent nulla massa, hendrerit vestibulum gravida in, feugiat auctor felis.',
                'visitor_registration_start_date' => '2024-12-29 21:40:45',
                'visitor_registration_end_date' => '2029-10-04 21:40:47',
                'exhibitor_registration_start_date' => '2025-02-28 21:40:49',
                'exhibitor_registration_end_date' => '2025-03-05 21:40:50',
                'website_url' => 'https://gdsc-estin.com',
                'contact' => '{"name":"Mouayed Keziz","email":"m_keziz@estin.dz","phone_number":"0799028574"}',
                'content' => '{"fr":"<p>g</p>","en":"<p>h</p>","ar":"<p>i</p>"}',
                'currencies' => '["EUR","DZD"]',
                'created_at' => '2025-02-15 20:41:16',
                'updated_at' => '2025-02-26 19:49:21',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}