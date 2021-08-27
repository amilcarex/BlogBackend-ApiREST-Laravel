<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SidebarImage;
class SideBarImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $image = new SidebarImage();
        $image->image = env('APP_URL') . '/storage/uploads/photos/sidebar-images/sidebar-1.jpg';
        $image->active = 0;
        $image->save();
        $image = new SidebarImage();
        $image->image = env('APP_URL') . '/storage/uploads/photos/sidebar-images/sidebar-2.jpg';
        $image->active = 1;
        $image->save();
        $image = new SidebarImage();
        $image->image = env('APP_URL') . '/storage/uploads/photos/sidebar-images/sidebar-3.jpg';
        $image->active = 0;
        $image->save();
        $image = new SidebarImage();
        $image->image = env('APP_URL') . '/storage/uploads/photos/sidebar-images/sidebar-4.jpg';
        $image->active = 0;
        $image->save();
        $image = new SidebarImage();
        $image->image = null;
        $image->active = 0;
        $image->save();
        $image = new SidebarImage();
        $image->image = null;
        $image->active = 0;
        $image->save();
        $image = new SidebarImage();
        $image->image = null;
        $image->active = 0;
        $image->save();
        $image = new SidebarImage();
        $image->image = null;
        $image->active = 0;
        $image->save();
    }
}
