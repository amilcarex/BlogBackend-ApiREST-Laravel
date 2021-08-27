<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class VisibilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('visibility_options')->insert([
            'id' => 1,
            'visibility' =>  'Draft'
        ]);
        DB::table('visibility_options')->insert([
            'id' => 2,
            'visibility' => 'Public'
        ]);
        DB::table('visibility_options')->insert([
            'id' => 3,
            'visibility' => 'Private'
        ]);
    }
}
