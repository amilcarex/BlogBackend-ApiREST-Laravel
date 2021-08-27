<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('general_settings')->insert([
            'id' => 1,
            'bgRegister' =>  env('APP_URL') .'/storage/uploads/photos/backgrounds/background-1.jpg',
            'bgLogin' =>  env('APP_URL') .'/storage/uploads/photos/backgrounds/background-2.jpg',
            'webTittle' => 'Feragon',
            'homeVideo' => null,
            'localVideo' => 1,
            'allowRegister' => 0,
            'defaultRole' => 2,
            'maxPostsToDisplay' => 10,
            'adminEmail' => 'admin@demo.com'
        ]);
    }
}
