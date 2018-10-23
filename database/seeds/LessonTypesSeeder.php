<?php

use Illuminate\Database\Seeder;

class LessonTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lesson_types')->insert([
            [
                'name' => "теория",
            ],[
                'name' => "практика",
            ]
        ]);
    }
}
