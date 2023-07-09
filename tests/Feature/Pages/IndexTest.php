<?php

namespace Tests\Feature\Pages;

use App\Models\Allergy;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Event;
use App\Models\Information;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Factory as Faker;

class IndexTest extends TestCase
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
    public function 사용자는_메인페이지에서_공개된_배너_목록을_조회할_수_있다()
    {
        $secretBanners = Banner::factory(["secret" => true])->count(10)->create();

        $publicBanners = Banner::factory(["secret" => false])->count(7)->create();

        $this->get("/")->assertInertia(function($page) use ($publicBanners){
            $items = $page->toArray()["props"]["banners"]["data"];

            $this->assertCount(count($publicBanners), $items);
        });
    }

    /** @test */
    public function 사용자는_메인페이지에서_식단_및_단품_목록을_조회할_수_있다()
    {
        $singleProducts = Product::factory()->count(7)->create([
            "isDiet" => false
        ]);

        $dietProducts = Product::factory()->count(4)->create([
            "isDiet" => true
        ]);

        $this->get("/")->assertInertia(function($page) use ($singleProducts, $dietProducts){
            $singles = $page->toArray()["props"]["singleProducts"]["data"];
            $diets = $page->toArray()["props"]["dietProducts"]["data"];

            $this->assertCount(count($singleProducts), $singles);
            $this->assertCount(count($dietProducts), $diets);
        });
    }

    /** @test */
    public function 사용자는_메인페이지에서_카테고리_목록을_조회할_수_있다()
    {
        $categories = Category::factory()->count(10)->create();

        $this->get("/")->assertInertia(function($page) use ($categories){
            $items = $page->toArray()["props"]["categories"]["data"];

            $this->assertCount(count($categories), $items);
        });
    }

    /** @test */
    public function 사용자는_메인페이지에서_알레르기유발성분_목록을_조회할_수_있다()
    {
        $allergies = Allergy::factory()->count(10)->create();

        $this->get("/")->assertInertia(function($page) use ($allergies){
            $items = $page->toArray()["props"]["allergies"]["data"];

            $this->assertCount(count($allergies), $items);
        });
    }

    /** @test */
    public function 특정_카테고리의_단품목록을_조회할_수_있다()
    {
        $categories = Category::factory()->count(2)->create();

        $productsA = Product::factory()->count(7)->create([
            "isDiet" => false
        ]);

        $productsB = Product::factory()->count(5)->create([
            "isDiet" => false
        ]);

        foreach($productsA as $product){
            $product->categories()->attach($categories[0]);
        }

        foreach($productsB as $product){
            $product->categories()->attach($categories[1]);
        }

        $this->json("get", "/", [
            "category_id" => $categories[0]->id
        ])->assertInertia(function($page) use ($productsA){
            $items = $page->toArray()["props"]["singleProducts"]["data"];

            $this->assertCount(count($productsA), $items);
        });

        $this->json("get", "/", [
            "category_id" => $categories[1]->id
        ])->assertInertia(function($page) use ($productsB){
            $items = $page->toArray()["props"]["singleProducts"]["data"];

            $this->assertCount(count($productsB), $items);
        });
    }

    /** @test */
    public function 특정_알레르기유발성분을_포함하지_않는_단품목록을_조회할_수_있다()
    {
        $allergies = Allergy::factory()->count(3)->create();


        $productsA = Product::factory()->count(5)->create([
            "isDiet" => false
        ]);
        foreach($productsA as $product){
            $product->allergies()->attach($allergies[0]->id);
        }


        $productsB = Product::factory()->count(6)->create([
            "isDiet" => false
        ]);
        foreach($productsB as $product){
            $product->allergies()->attach($allergies[1]->id);
            $product->allergies()->attach($allergies[2]->id);
        }


        $productsC = Product::factory()->count(3)->create([
            "isDiet" => false
        ]);
        foreach($productsC as $product){
            $product->allergies()->attach($allergies[2]->id);
        }


        $this->json("get", "/", [
            "allergy_ids" => [$allergies[0]->id]
        ])->assertInertia(function($page) use ($productsA, $productsB, $productsC){
            $items = $page->toArray()["props"]["singleProducts"]["data"];

            $this->assertCount(count($productsB) + count($productsC), $items);
        });

        $this->json("get", "/", [
            "allergy_ids" => [$allergies[1]->id]
        ])->assertInertia(function($page) use ($productsA, $productsB, $productsC, $allergies){
            $items = $page->toArray()["props"]["singleProducts"]["data"];

            $this->assertCount(count($productsA) + count($productsC), $items);
        });

        $this->json("get", "/", [
            "allergy_ids" => [$allergies[2]->id]
        ])->assertInertia(function($page) use ($productsA, $productsB, $productsC){
            $items = $page->toArray()["props"]["singleProducts"]["data"];

            $this->assertCount(count($productsA), $items);
        });
    }

    /** @test */
    public function 인기순_단품목록을_조회할_수_있다()
    {
        $singleProducts = Product::factory()->count(7)->create([
            "isDiet" => false
        ]);

        $this->json("get", "/", [
            "orderBy" => "count_order",
            "align" => "desc"
        ])->assertInertia(function($page){
            $items = $page->toArray()["props"]["singleProducts"]["data"];

            $prev = null;

            foreach($items as $item){
                if($prev)
                    $this->assertTrue($prev["count_order"] > $item["count_order"]);

                $prev = $item;
            }
        });
    }

    /** @test */
    public function 최신순_단품목록을_조회할_수_있다()
    {
        Product::factory()->create([
            "isDiet" => false,
            "created_at" => Carbon::now()
        ]);

        Product::factory()->create([
            "isDiet" => false,
            "created_at" => Carbon::now()->subDays(2)
        ]);

        Product::factory()->create([
            "isDiet" => false,
            "created_at" => Carbon::now()->addDays(2)
        ]);

        $this->json("get", "/", [
            "orderBy" => "created_at",
            "align" => "desc"
        ])->assertInertia(function($page){
            $items = $page->toArray()["props"]["singleProducts"]["data"];

            $prev = null;

            foreach($items as $item){
                if($prev)
                    $this->assertTrue($prev["created_at"] > $item["created_at"]);

                $prev = $item;
            }
        });
    }

    /** @test */
    public function 특정_단어를_포함한_단품목록을_조회할_수_있다()
    {
        $word = "test";

        $includeProducts = Product::factory()->count(8)->create([
            "isDiet" => false,
        ]);

        foreach($includeProducts as $product){
            $product->update(["title" => $product->title.$word]);
        }

        $excludeProducts = Product::factory()->create([
            "isDiet" => false,
        ]);

        $this->json("get", "/", [
            "word" => $word,
        ])->assertInertia(function($page) use ($includeProducts){
            $items = $page->toArray()["props"]["singleProducts"]["data"];

            $this->assertCount(count($includeProducts), $items);
        });
    }
}
