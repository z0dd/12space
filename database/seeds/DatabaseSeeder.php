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
         $this->call([
             GendersTableSeeder::class,
             AccountsSeeder::class,
             CourseStatusesSeeder::class,
             FileTypesSeeder::class,
             LessonTypesSeeder::class,
             LogTypesSeeder::class,
         ]);
    }
}
