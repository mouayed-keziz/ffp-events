<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IseedVisitorSubmissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('visitor_submissions')->delete();
        
        \DB::table('visitor_submissions')->insert(array (
            0 => 
            array (
                'id' => 14,
                'visitor_id' => 5,
                'event_announcement_id' => 2,
                'answers' => '[{"title":{"fr":"first","en":"first","ar":"first"},"fields":[{"type":"input","data":{"label":{"fr":"title","en":"title","ar":"title"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},"answer":"azda"},{"type":"upload","data":{"label":{"fr":"pdf","en":"pdf","ar":"pdf"},"description":{"fr":null,"en":null,"ar":null},"file_type":"pdf"},"answer":"65b397e5-8da3-4533-949e-007e8c67a3d1"},{"type":"select","data":{"label":{"fr":"aaa","en":"bbbb","ar":"ccc"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"a","en":"a","ar":"a"}},{"option":{"fr":"b","en":"b","ar":"b"}},{"option":{"fr":"c","en":"c","ar":"c"}}]},"answer":{"fr":"b","en":"b","ar":"b"}}]}]',
                'status' => 'approved',
                'created_at' => '2025-02-27 15:56:55',
                'updated_at' => '2025-02-27 15:56:55',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 15,
                'visitor_id' => 5,
                'event_announcement_id' => 2,
                'answers' => '[{"title":{"fr":"first","en":"first","ar":"first"},"fields":[{"type":"input","data":{"label":{"fr":"title","en":"title","ar":"title"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},"answer":"azdazd"},{"type":"upload","data":{"label":{"fr":"pdf","en":"pdf","ar":"pdf"},"description":{"fr":null,"en":null,"ar":null},"file_type":"image"},"answer":"ab734d7b-807b-4dc9-98b1-b544832be19d"},{"type":"select","data":{"label":{"fr":"aaa","en":"bbbb","ar":"ccc"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"a","en":"a","ar":"a"}},{"option":{"fr":"b","en":"b","ar":"b"}},{"option":{"fr":"c","en":"c","ar":"c"}}]},"answer":{"fr":"c","en":"c","ar":"c"}}]}]',
                'status' => 'approved',
                'created_at' => '2025-02-27 16:23:49',
                'updated_at' => '2025-02-27 16:23:49',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 16,
                'visitor_id' => 5,
                'event_announcement_id' => 2,
                'answers' => '[{"title":{"fr":"first","en":"first","ar":"first"},"fields":[{"type":"input","data":{"label":{"fr":"title","en":"title","ar":"title"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},"answer":"azd"},{"type":"upload","data":{"label":{"fr":"pdf","en":"pdf","ar":"pdf"},"description":{"fr":null,"en":null,"ar":null},"file_type":"pdf"},"answer":"0d539019-898c-4db8-a041-9c7854557677"},{"type":"select","data":{"label":{"fr":"aaa","en":"bbbb","ar":"ccc"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"a","en":"a","ar":"a"}},{"option":{"fr":"b","en":"b","ar":"b"}},{"option":{"fr":"c","en":"c","ar":"c"}}]},"answer":{"ar":""}}]}]',
                'status' => 'approved',
                'created_at' => '2025-02-27 16:25:53',
                'updated_at' => '2025-02-27 16:25:53',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 17,
                'visitor_id' => 5,
                'event_announcement_id' => 2,
                'answers' => '[{"title":{"fr":"first","en":"first","ar":"first"},"fields":[{"type":"input","data":{"label":{"fr":"title","en":"title","ar":"title"},"description":{"fr":null,"en":null,"ar":null},"type":"text","required":true},"answer":"444"},{"type":"upload","data":{"label":{"fr":"pdf","en":"pdf","ar":"pdf"},"description":{"fr":null,"en":null,"ar":null},"file_type":"any"},"answer":"af50d997-bdd2-48eb-a2e1-c1e3e9cb8959"},{"type":"select","data":{"label":{"fr":"aaa","en":"bbbb","ar":"ccc"},"description":{"fr":null,"en":null,"ar":null},"options":[{"option":{"fr":"a","en":"a","ar":"a"}},{"option":{"fr":"b","en":"b","ar":"b"}},{"option":{"fr":"c","en":"c","ar":"c"}}]},"answer":{"fr":"b","en":"b","ar":"b"}}]}]',
                'status' => 'approved',
                'created_at' => '2025-02-27 16:31:09',
                'updated_at' => '2025-02-27 16:31:09',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}