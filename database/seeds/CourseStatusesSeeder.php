<?php

use Illuminate\Database\Seeder;

class CourseStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_statuses')->insert([
            [
                'name' => "базовый",
            ],[
                'name' => "продвинутый",
            ]
        ]);
    }
}
