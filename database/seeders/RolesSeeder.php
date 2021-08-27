<?php

namespace Database\Seeders;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role = new Role();
        $role->id = 1;
        $role->name = 'Admin';
        $role->description = 'Administrador';
        $role->save();
        $role = new Role();
        $role->id = 2;
        $role->name = 'User';
        $role->description = 'Usuario';
        $role->save();
    }
}
