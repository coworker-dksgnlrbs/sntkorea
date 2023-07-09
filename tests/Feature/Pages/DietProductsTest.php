<?php

namespace Tests\Feature;

use App\Models\Allergy;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DietProductsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);
    }

    /** @test */
    public function 식단목록페이지에서_식단_목록을_조회할_수_있다()
    {
        $products = Product::factory()->count(7)->create([
            "isDiet" => true
        ]);

        $this->get("/dietProducts")->assertInertia(function($page) use ($products){
            $items = $page->toArray()["props"]["dietProducts"]["data"];

            $this->assertCount(count($products), $items);
        });
    }
}
