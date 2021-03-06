<?php
namespace Database\Seeders;

use App\Models\SidebarImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $this->call(RolesSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(SideBarImagesSeeder::class);
        $this->call(PermissionPostsSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(VisibilitySeeder::class);
        $this->call(SocialNetworkSeeder::class);
    }
}
