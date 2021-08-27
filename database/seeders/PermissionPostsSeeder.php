<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermissionPost;
class PermissionPostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $image = new PermissionPost();
        $image->id = 1;
        $image->permission = 'pinned';
        $image->save();

        $image = new PermissionPost();
        $image->id = 2;
        $image->permission = 'allowComments';
        $image->save();
        
        $image = new PermissionPost();
        $image->id = 3;
        $image->permission = 'restricted';
        $image->save();
    }
}
