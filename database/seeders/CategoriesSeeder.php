<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            "English",
            "Mathematics",
            "Science",
            "SocialStudies",
            "PhysicalEducation",
            "WorldLanguages",
            "Electives",
        ];

        foreach($categories as $category){
            Category::create(["title" => $category]);
        }
    }
}
