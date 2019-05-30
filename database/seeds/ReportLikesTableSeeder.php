<?php

use App\ReportLike;
use Illuminate\Database\Seeder;

class ReportLikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {

        for ($user = 1; $user <= config('app.users'); $user++) {
            for ($report = 1; $report <= config('app.reports'); $report++) {

                $rnd = random_int(0,1);

                if($rnd) continue;

                ReportLike::create([

                    'user_id' => $user,
                    'report_id' => $report,

                ]);
            }
        }
    }
}
