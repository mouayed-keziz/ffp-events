<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BACKUPProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('products')->delete();

        \DB::table('products')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => '{"fr":"Table scandinave ","en":"Table scandinave ","ar":"Table scandinave "}',
                'code' => '123',
                'created_at' => '2025-04-08 13:45:43',
                'updated_at' => '2025-04-13 13:10:10',
                'deleted_at' => '2025-04-13 13:10:10',
            ),
            1 =>
            array(
                'id' => 2,
                'name' => '{"fr":"électricité ","en":"electricity","ar":"كهرباء"}',
                'code' => '1',
                'created_at' => '2025-04-09 10:49:57',
                'updated_at' => '2025-04-09 11:04:27',
                'deleted_at' => '2025-04-09 11:04:27',
            ),
            2 =>
            array(
                'id' => 3,
                'name' => '{"fr":"Electricité","ar":null}',
                'code' => 'electricite_siffp_2025',
                'created_at' => '2025-04-09 11:03:30',
                'updated_at' => '2025-04-09 11:03:30',
                'deleted_at' => NULL,
            ),
            3 =>
            array(
                'id' => 4,
                'name' => '{"fr":"EMPLACEMENT NON AMÉNAGÉ (min 24 m²)  Sol nu & traçage au sol uniquement  électricité 220V","en":"Undeveloped site (min. 24 m²) Bare ground & floor markings only 220V electricity","ar":"موقع غير مطور (الحد الأدنى 24 مترًا مربعًا) علامات الأرض والأرضية العارية فقط كهرباء 220 فولت"}',
                'code' => 'EMPLACEMENT NON AMÉNAGÉ',
                'created_at' => '2025-04-09 12:37:31',
                'updated_at' => '2025-04-09 12:37:31',
                'deleted_at' => NULL,
            ),
            4 =>
            array(
                'id' => 5,
                'name' => '{"fr":" EMPLACEMENT AMÉNAGÉ (MIN 9 m²)  Stand basique structure truss cloison bâches  ,moquette, spots, bandeau de signalisation,  table, 3 chaises , raccordement électrique","en":"FURNISHED LOCATION (MIN. 9 m²) Basic stand with truss structure, tarpaulin partition, carpet, spotlights, signage, table, 3 chairs, electrical connection","ar":"منصة مفروشة (الحد الأدنى 9 متر مربع)موقع مفروش (الحد الأدنى 9 متر مربع) هيكل الحامل الأساسي، عوارض خشبية، حواجز قماشية ، السجاد، الأضواء، شريط الإشارات، طاولة، 3 كراسي، توصيل كهربائي"}',
                'code' => ' EMPLACEMENT AMÉNAGÉ ',
                'created_at' => '2025-04-09 12:43:35',
                'updated_at' => '2025-04-09 12:44:41',
                'deleted_at' => NULL,
            ),
            5 =>
            array(
                'id' => 6,
                'name' => '{"fr":" STAND CLE EN MAIN , stand basique en MDF comprend: Moquette,  logo en Forex, électrique générale   ( 4 projecteur / spot) 2 prises","en":"TURNKEY STAND, basic MDF stand includes: Carpet, Forex logo, general electrics (4 projectors / spotlights) 2 sockets","ar":"حامل جاهز للاستخدام، حامل MDF أساسي يتضمن: سجادة، شعار فوركس، شركة جنرال إلكتريك (4 أجهزة عرض / كشافات) 2 مقبس"}',
                'code' => 'STAND CLE EN MAIN ',
                'created_at' => '2025-04-09 12:46:15',
                'updated_at' => '2025-04-09 12:46:15',
                'deleted_at' => NULL,
            ),
            6 =>
            array(
                'id' => 7,
                'name' => '{"fr":"EMPLACEMENT EXTÉRIEUR (MIN 12 m²), Traçage au sol  uniquement","en":"OUTDOOR LOCATION (MIN 12 m²), Floor markings only","ar":"الموقع الخارجي (الحد الأدنى 12 مترًا مربعًا)، علامات الأرضية فقط"}',
                'code' => 'EMPLACEMENT EXTÉRIEUR (MIN 12 m²)',
                'created_at' => '2025-04-09 12:47:31',
                'updated_at' => '2025-04-09 12:47:31',
                'deleted_at' => NULL,
            ),
            7 =>
            array(
                'id' => 8,
                'name' => '{"fr":" FAÇADE EN L","ar":null}',
                'code' => ' FAÇADE EN L SIFFP2025',
                'created_at' => '2025-04-09 14:17:12',
                'updated_at' => '2025-04-09 14:20:51',
                'deleted_at' => '2025-04-09 14:20:51',
            ),
            8 =>
            array(
                'id' => 9,
                'name' => '{"fr":"Façade en L","en":"L-shaped facade","ar":"واجهة على شكل حرف L"}',
                'code' => 'Façade en L SIFFP 2025',
                'created_at' => '2025-04-09 14:23:48',
                'updated_at' => '2025-04-09 14:23:48',
                'deleted_at' => NULL,
            ),
            9 =>
            array(
                'id' => 10,
                'name' => '{"fr":" FAÇADE EN U","ar":"واجهة على شكل حرف U"}',
                'code' => ' FAÇADE EN U SIFFP2025',
                'created_at' => '2025-04-09 14:27:54',
                'updated_at' => '2025-04-09 14:27:54',
                'deleted_at' => NULL,
            ),
            10 =>
            array(
                'id' => 11,
                'name' => '{"fr":"4 FAÇADE OUVERTES/ ILOTS","ar":null}',
                'code' => '4 FAÇADE OUVERTES/ ILOTS siffp2025',
                'created_at' => '2025-04-09 14:29:52',
                'updated_at' => '2025-04-09 14:29:52',
                'deleted_at' => NULL,
            ),
            11 =>
            array(
                'id' => 12,
                'name' => '{"fr":"Chaise ","en":"Chair","ar":"كرسي"}',
                'code' => 'Chaise_siffp2025',
                'created_at' => '2025-04-13 12:55:04',
                'updated_at' => '2025-04-13 12:55:04',
                'deleted_at' => NULL,
            ),
            12 =>
            array(
                'id' => 13,
                'name' => '{"fr":"Table Scandinave","en":"Scandinavian table","ar":"طاولة اسكندنافية"}',
                'code' => 'Table Scandinave_siffp2025',
                'created_at' => '2025-04-13 12:56:07',
                'updated_at' => '2025-04-13 12:56:07',
                'deleted_at' => NULL,
            ),
            13 =>
            array(
                'id' => 14,
                'name' => '{"fr":"téléviseur 44\\"","en":"TV 44*","ar":"تلفزيون 44*"}',
                'code' => 'téléviseur_siffp2025',
                'created_at' => '2025-04-13 13:06:42',
                'updated_at' => '2025-05-17 14:46:18',
                'deleted_at' => NULL,
            ),
            14 =>
            array(
                'id' => 15,
                'name' => '{"fr":"Tapis ","en":"Carpet ","ar":"سجاد"}',
                'code' => 'Tapis_siffp2025',
                'created_at' => '2025-04-13 13:09:16',
                'updated_at' => '2025-04-13 13:09:16',
                'deleted_at' => NULL,
            ),
            16 =>
            array(
                'id' => 17,
                'name' => '{"fr":"Machine a Café ","en":"Coffee machine","ar":"ماكينة القهوة"}',
                'code' => 'Machine a Café _siffp2025',
                'created_at' => '2025-04-13 13:23:01',
                'updated_at' => '2025-04-13 13:23:01',
                'deleted_at' => NULL,
            ),
            17 =>
            array(
                'id' => 18,
                'name' => '{"fr":"Corbeille ","en":"Basket","ar":"سلة"}',
                'code' => 'Corbeille_siffp2025',
                'created_at' => '2025-04-13 13:26:00',
                'updated_at' => '2025-04-13 13:26:00',
                'deleted_at' => NULL,
            ),
            18 =>
            array(
                'id' => 19,
                'name' => '{"fr":"Porte manteau","en":"Coat rack","ar":"شماعة المعاطف"}',
                'code' => 'Porte mantea_siffp2025',
                'created_at' => '2025-04-13 13:27:35',
                'updated_at' => '2025-04-13 13:27:35',
                'deleted_at' => NULL,
            ),
            19 =>
            array(
                'id' => 20,
                'name' => '{"fr":"imprimantes","en":"printers","ar":"الطابعات"}',
                'code' => 'imprimantes_siffp2025',
                'created_at' => '2025-04-13 13:29:17',
                'updated_at' => '2025-04-13 13:29:17',
                'deleted_at' => NULL,
            ),
            20 =>
            array(
                'id' => 21,
                'name' => '{"fr":"lustre","en":"chandelier","ar":"الثريا"}',
                'code' => 'lustre_siffp2025',
                'created_at' => '2025-04-13 13:39:16',
                'updated_at' => '2025-04-13 13:39:16',
                'deleted_at' => NULL,
            ),
            21 =>
            array(
                'id' => 22,
                'name' => '{"fr":"fauteuils ","en":"armchairs","ar":"اركة"}',
                'code' => 'fauteuils_siffp2025',
                'created_at' => '2025-04-14 13:34:25',
                'updated_at' => '2025-04-14 13:34:25',
                'deleted_at' => NULL,
            ),
            22 =>
            array(
                'id' => 23,
                'name' => '{"fr":"plantes artificielles Grand format / 1.50 largeur / Pot diamètre 30 / Dim 34/30","en":"large format artificial plants","ar":"نباتات اصطناعية كبيرة الحجم"}',
                'code' => 'plantes artificielles_siffp2025',
                'created_at' => '2025-04-14 13:35:47',
                'updated_at' => '2025-05-21 12:46:17',
                'deleted_at' => NULL,
            ),

            24 =>
            array(
                'id' => 25,
                'name' => '{"fr":"mini frigo","en":"mini fridge","ar":"ثلاجة صغيرة"}',
                'code' => 'mini frigo_siffp2025',
                'created_at' => '2025-04-14 13:36:49',
                'updated_at' => '2025-04-14 13:36:49',
                'deleted_at' => NULL,
            ),
            25 =>
            array(
                'id' => 26,
                'name' => '{"fr":"Téléviseur 55\\"","en":"55\\" TV","ar":"تلفزيون 55 بوصة"}',
                'code' => 'Téléviseur 55"_siffp2025',
                'created_at' => '2025-04-14 14:12:04',
                'updated_at' => '2025-04-14 14:12:04',
                'deleted_at' => NULL,
            ),
            26 =>
            array(
                'id' => 27,
                'name' => '{"fr":"Téléviseur 65\\" ","en":"65\\" TV","ar":"تلفزيون 65 بوصة"}',
                'code' => 'Téléviseur 65" _siffp2025',
                'created_at' => '2025-04-14 14:13:34',
                'updated_at' => '2025-04-14 14:13:34',
                'deleted_at' => NULL,
            ),
            27 =>
            array(
                'id' => 28,
                'name' => '{"fr":"téléviseur 75\\"","en":"75\\" television","ar":"تلفزيون 75 بوصة"}',
                'code' => 'téléviseur 75"_siffp2025',
                'created_at' => '2025-04-14 14:14:55',
                'updated_at' => '2025-04-14 14:14:55',
                'deleted_at' => NULL,
            ),
            28 =>
            array(
                'id' => 29,
                'name' => '{"fr":"plantes artificielles Petit format ","en":"artificial plants Small format","ar":"نباتات اصطناعية حجم صغير"}',
                'code' => 'plantes artificielles Petit format_siffp2025',
                'created_at' => '2025-04-14 14:36:34',
                'updated_at' => '2025-05-21 12:45:55',
                'deleted_at' => NULL,
            ),
            29 =>
            array(
                'id' => 30,
                'name' => '{"fr":"Chaise haute ","en":"high chair","ar":"Chaise haute "}',
                'code' => 'Chaisehaute_siffp2025',
                'created_at' => '2025-05-15 18:46:31',
                'updated_at' => '2025-05-15 18:46:31',
                'deleted_at' => NULL,
            ),
            30 =>
            array(
                'id' => 31,
                'name' => '{"fr":"Téléviseur 70\\"","en":"70\\" TV","ar":"تلفزيون 70 بوصة"}',
                'code' => 'Téléviseur 70"_siffp2025',
                'created_at' => '2025-05-18 10:12:56',
                'updated_at' => '2025-05-18 10:12:56',
                'deleted_at' => NULL,
            ),
            31 =>
            array(
                'id' => 32,
                'name' => '{"fr":"49\\" TV","ar":"تلفزيون 49 بوصة"}',
                'code' => 'Téléviseur 49"_siffp2025',
                'created_at' => '2025-05-18 10:15:21',
                'updated_at' => '2025-05-18 10:15:21',
                'deleted_at' => NULL,
            ),
            32 =>
            array(
                'id' => 33,
                'name' => '{"fr":"Téléviseur 32\\"","en":"تلفزيون 32 بوصة","ar":null}',
                'code' => 'Téléviseur 32"_siffp2025',
                'created_at' => '2025-05-18 10:15:24',
                'updated_at' => '2025-05-18 10:15:24',
                'deleted_at' => NULL,
            ),
            33 =>
            array(
                'id' => 34,
                'name' => '{"fr":"Table Haute","en":"High Table","ar":"طاولة عالية"}',
                'code' => 'Table Haute_siffp2025',
                'created_at' => '2025-05-18 10:35:08',
                'updated_at' => '2025-05-18 10:35:08',
                'deleted_at' => NULL,
            ),
            34 =>
            array(
                'id' => 35,
                'name' => '{"fr":"Meuble de Rangement","en":"Storage unit","ar":"خزانة تخزين"}',
                'code' => 'Meuble de Rangement_siffp2025',
                'created_at' => '2025-05-18 10:53:17',
                'updated_at' => '2025-05-18 10:53:17',
                'deleted_at' => NULL,
            ),
            35 =>
            array(
                'id' => 36,
                'name' => '{"fr":"Porte document","en":"Document holder","ar":"حافظة مستندات"}',
                'code' => 'Porte document_siffp2025',
                'created_at' => '2025-05-18 11:00:06',
                'updated_at' => '2025-05-18 11:00:06',
                'deleted_at' => NULL,
            ),
            36 =>
            array(
                'id' => 37,
                'name' => '{"fr":"Vitrine 1m x 0.5m","en":"Glass display case 1m x 0.5m","ar":"خزانة عرض بحجم 1م × 0.5م"}',
                'code' => 'Vitrine 1m x 0.5m_siffp2025',
                'created_at' => '2025-05-18 11:00:42',
                'updated_at' => '2025-05-18 11:00:42',
                'deleted_at' => NULL,
            ),
            37 =>
            array(
                'id' => 38,
                'name' => '{"fr":"Multiprise","en":"Power strip","ar":"وصلة كهربائية"}',
                'code' => 'Multiprise_siffp2025',
                'created_at' => '2025-05-18 11:01:34',
                'updated_at' => '2025-05-18 11:01:34',
                'deleted_at' => NULL,
            ),
            38 =>
            array(
                'id' => 39,
                'name' => '{"fr":"Desk de réception","en":"Reception Desk","ar":"مكتب استقبال"}',
                'code' => 'Desk de réception_siffp2025',
                'created_at' => '2025-05-18 11:02:25',
                'updated_at' => '2025-05-18 11:02:25',
                'deleted_at' => NULL,
            ),
        ));
    }
}
