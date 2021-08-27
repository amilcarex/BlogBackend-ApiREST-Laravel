<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('statuses')->insert([
            'id' => 1,
            'name' =>  'Created'
        ]);
        DB::table('statuses')->insert([
            'id' => 2,
            'name' =>  'In Progress'
        ]);
        DB::table('statuses')->insert([
            'id' => 3,
            'name' =>  'Completed'
        ]);
    }
}
