<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class, 10)->create();

        $category = Category::find(1);
        $category->products()->sync([1, 2, 3, 4, 5]);
    }
}
