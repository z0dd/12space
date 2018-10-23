<?php

use Illuminate\Database\Seeder;

class LogTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('logger_types')->insert([
            [
                'name' => "error",
            ],[
                'name' => "warning",
            ],[
                'name' => "information",
            ]
        ]);
    }
}
