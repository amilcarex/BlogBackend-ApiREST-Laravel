<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        User::create([
            'id' => 4,
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'image' => env('APP_URL').'/storage/placeholder/default-avatar.png',
            'password' => 'demo',
            'show' => 1,
            'admin'  => 1
        ])->roles()->sync(1);
    }
}
