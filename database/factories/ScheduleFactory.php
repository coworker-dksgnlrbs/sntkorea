<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $titles = [
            "강원도민 체육대회",
            "양구군 체육대회",
            "마라톤 전국일주",
            "양구주민 축구대회"
        ];

        $count = rand(1,100);

        return [
            "title" => "제{$count}회 ".$titles[rand(0, count($titles) - 1)],
            "place" => "양구국민체육센터",
            "count_participant" => "1000",
            "host" => "양구군",
            "management" => "잇다컴퍼니",
            "event" => "축구",
            "started_at" => Carbon::now()->subDays(rand(1, 10)),
            "finished_at" => Carbon::now()->addDays(rand(1, 10)),
        ];
    }
}
