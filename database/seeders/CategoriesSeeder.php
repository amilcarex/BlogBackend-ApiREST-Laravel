<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $category = new Category();
        $category->name = 'Uncategorized';
        $category->slug = 'uncategorized';
        $category->default = 1;
        $category->save();
    }
}
