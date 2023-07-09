<?php

namespace Tests\Feature\Pages;

use App\Models\Allergy;
use App\Models\Banner;
use App\Models\Basic;
use App\Models\Nutrition;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SingleProductShowTest extends TestCase
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
    public function 상품상세페이지에서_상품을_조회할_수_있다()
    {
        $product = Product::factory()->create([
            "isDiet" => false
        ]);

        $this->get("/singleProducts/".$product->id)->assertInertia(function($page){
            $item = $page->toArray()["props"]["product"]["data"];

            $this->assertNotNull($item);
        });
    }

    /** @test */
    public function 상품상세페이지에서_상품의_제품상세정보를_조회할_수_있다()
    {
        $product = Product::factory()->create([
            "isDiet" => false
        ]);

        $basic = Basic::factory()->create([
            "product_id" => $product->id
        ]);

        $this->get("/singleProducts/".$product->id)->assertInertia(function($page){
            $item = $page->toArray()["props"]["product"]["data"];

            $this->assertNotNull($item["basic"]);
        });
    }

    /** @test */
    public function 상품상세페이지에서_상품의_제품영양정보를_조회할_수_있다()
    {
        $product = Product::factory()->create([
            "isDiet" => false
        ]);

        $nutrition = Nutrition::factory()->create([
            "product_id" => $product->id
        ]);

        $this->get("/singleProducts/".$product->id)->assertInertia(function($page){
            $item = $page->toArray()["props"]["product"]["data"];

            $this->assertNotNull($item["nutrition"]);
        });
    }

    /** @test */
    public function 상품상세페이지에서_상품의_알러지유발식품을_조회할_수_있다()
    {
        $product = Product::factory()->create([
            "isDiet" => false
        ]);

        $allergy = Allergy::factory()->create();

        $product->allergies()->attach($allergy);

        $this->get("/singleProducts/".$product->id)->assertInertia(function($page){
            $item = $page->toArray()["props"]["product"]["data"];

            $this->assertTrue(count($item["allergies"]) > 0);
        });
    }

    /** @test */
    public function 상품상세페이지에서_알러지목록을_조회할_수_있다()
    {
        $product = Product::factory()->create([
            "isDiet" => false
        ]);

        $allergies = Allergy::factory()->count(10)->create();

        $this->get("/singleProducts/".$product->id)->assertInertia(function($page) use($allergies){
            $items = $page->toArray()["props"]["allergies"]["data"];

            $this->assertCount(count($allergies), $items);
        });
    }
}
