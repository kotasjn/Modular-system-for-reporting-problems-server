<?php

use App\CommentLike;
use Illuminate\Database\Seeder;

class CommentLikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        for ($comment = 1; $comment <= 1000; $comment++) {
            for ($user = 1; $user <= 10; $user++) {

                $rnd = random_int(1, 5);

                if ($rnd > 1) continue;

                CommentLike::create([

                    'user_id' => $user,
                    'comment_id' => $comment,

                ]);
            }
        }
    }
}
