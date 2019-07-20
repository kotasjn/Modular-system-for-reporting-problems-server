<?php

use App\CommentLike;
use Illuminate\Database\Seeder;

class CommentLikesTableSeeder extends Seeder
{
    /**
     * Spuštění seedu pro model CommentLike.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        for ($comment = 1; $comment <= config('app.comments'); $comment++) {
            for ($user = 1; $user <= config('app.users'); $user++) {

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
