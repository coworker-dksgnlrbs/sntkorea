<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = Category::first() ?? Category::factory()->create();

        return [
            "category_id" => $category->id,
            "title" => $this->faker->title,
            "contact" => "033-000-0000",
            "address" => "양구군 양구읍 파로호로 992-6",
            "description" => "<img src='/images/ready.png' alt='' class='ready' style='max-width:100%; display: block; margin:0 auto;'>",
        ];
    }
}
