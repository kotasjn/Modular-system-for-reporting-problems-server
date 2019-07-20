<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Hlavní seed, který spouští ostatní seedy pro konkrétní modely.
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
        $this->call(ModuleTableSeeder::class);
        $this->call(InputTableSeeder::class);
        $this->call(ItemTableSeeder::class);
    }
}
