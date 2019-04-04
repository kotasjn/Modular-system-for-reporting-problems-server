<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(TerritoriesTableSeeder::class);
        $this->call(ReportsTableSeeder::class);
        $this->call(ReportPhotosTableSeeder::class);
        $this->call(ReportLikesTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(CommentLikesTableSeeder::class);
    }
}
