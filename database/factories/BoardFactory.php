<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BoardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $companies = ["경향신문", "서울신문", "연합뉴스", "뉴스로", "양구신문"];

        return [
            "title" => $this->faker->title,
            "description" => $this->faker->paragraph,
            "company" => $companies[rand(0, count($companies) - 1)],
            "url" => "https://www.yna.co.kr/view/AKR20220704058000062"
        ];
    }
}
