<?php

use Illuminate\Database\Seeder;

class FileTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('file_types')->insert([
            [
                'name' => "изображение",
                'mimes' => 'jpg|jpeg|png',
            ],[
                'name' => "аудио",
                'mimes' => 'mp3|wav',
            ],[
                'name' => "видео",
                'mimes' => 'mov|mp4',
            ]
        ]);
    }
}
