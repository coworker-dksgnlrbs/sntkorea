<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\DeliveryAmount;
use App\Models\DeliveryDate;
use App\Models\DeliveryDuration;
use App\Models\Diet;
use App\Models\Holidate;
use App\Models\Holiday;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartsTest extends TestCase
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
    public function 최소배송무료금액과_배송비를_조회할_수_있다()
    {
        $cart = Cart::factory()->create([
            "user_id" => $this->user->id
        ]);

        $this->get("/carts")->assertInertia(function($page){
            $minPrice = $page->toArray()["props"]["delivery_min_discount_price"];
            $deliveryPrice = $page->toArray()["props"]["delivery_price"];

            $this->assertEquals($minPrice, config("setting.delivery_min_discount_price"));
            $this->assertEquals($deliveryPrice, config("setting.delivery_price"));
        });

    }


    /** @test */
    public function 장바구니_목록을_조회할_때_회차상품은_대표상품_한개만_노출된다()
    {

    }

    /** @test */
    public function 사용자는_상품을_장바구니에_담을_수_있다()
    {
        $product = Product::factory()->create();

        $count = 4;

        $this->post("/carts", [
            "isDiet" => 0,
            "product_id" => $product->id,
            "count" => $count
        ]);

        auth()->user()->load("cart");

        // 상품 종류는 1개가 담기고, 그 상품의 개수가 4개
        $this->assertEquals(1, auth()->user()->cart->products()->count());

        $this->assertEquals($count,auth()->user()->cart->products()->sum("count"));

    }

    /** @test */
    public function 같은_단품을_장바구니에_다시_담으면_해당_상품의_개수가_증가한다()
    {
        $product = Product::factory()->create();

        $count = 4;

        $this->post("/carts", [
            "isDiet" => 0,
            "product_id" => $product->id,
            "count" => $count
        ]);

        auth()->user()->load("cart");

        // 상품 종류는 1개가 담기고, 그 상품의 개수가 4개
        $this->assertEquals(1, auth()->user()->cart->products()->count());

        $this->assertEquals($count,auth()->user()->cart->products()->sum("count"));
    }


    /** @test */
    public function 사용자는_단품의_개수를_변경할_수_있다()
    {
        $product = Product::factory()->create();

        $count = 4;

        $prevDeliveryAt = Carbon::now()->addDays(3);

        $this->post("/carts", [
            "isDiet" => 0,
            "product_id" => $product->id,
            "count" => $count,
            "delivery_at" => $prevDeliveryAt
        ]);

        $cart = $this->user->load("cart")->cart;

        $product = $cart->products()->find($product->id);

        $changeCount = 11;

        $this->json("patch", "/carts", [
            "count" => $changeCount,
            "product_id" => $product->id
        ]);

        $product = $cart->products()->find($product->id);

        $this->assertEquals($changeCount, $product->pivot->count);
    }

    /** @test */
    public function 사용자는_식단의_개수를_변경할_수_있다()
    {
        $diet = Diet::factory()->create([
            "user_id" => $this->user->id
        ]);

        $products = Product::factory()->count(4)->create([
            "diet_id" => $diet->id
        ]);

        $this->post("/carts", [
            "isDiet" => 1,
            "diet_id" => $diet->id,
        ]);

        $cart = $this->user->load("cart")->cart;

        $product = $cart->products()->find($products[0]->id);

        $changeCount = 11;

        $this->json("patch", "/carts", [
            "count" => $changeCount,
            "product_id" => $product->id
        ]);

        $products = $cart->products()->whereIn("products.id", $product->diet->products()->pluck("id"))->get();

        foreach($products as $product){
            $this->assertEquals($changeCount, $product->pivot->count);
        }
    }


    /** @test */
    public function 장바구니에_담아놓은_배송시작일이_지난_식단은_해당_모든_회차가_주문불가_처리된다()
    {
        // 2일 내
        $cart = Cart::factory()->create([
            "user_id" => $this->user->id
        ]);

        $expiredDiet = Diet::factory()->create([
            "delivery_started_at" => Carbon::now()->addDay()
        ]);

        $roundProducts = Product::factory()->count(7)->create([
            "diet_id" => $expiredDiet->id,
        ]);

        $this->get("/carts");

        foreach($roundProducts as $roundProduct){
            $this->assertEquals($roundProduct->can_order, 0);
        }


        // 휴일
        $holidate = Holidate::factory()->create([
            "closed_at" => Carbon::now()->addWeek()
        ]);

        $expiredDiet = Diet::factory()->create([
            "delivery_started_at" => $holidate->closed_at
        ]);

        $roundProducts = Product::factory()->count(7)->create([
            "diet_id" => $expiredDiet->id,
        ]);

        $this->get("/carts");

        foreach($roundProducts as $roundProduct){
            $this->assertEquals($roundProduct->can_order, 0);
        }
    }

    /** @test */
    public function 사용자는_자신의_장바구니에서_여러상품을_제거할_수_있다()
    {
        $cart = Cart::factory([
            "user_id" => $this->user->id
        ])->create();

        $products = Product::factory()->count(11)->create();

        $cart->products()->attach($products->pluck("id"));

        $this->json("delete", "/carts/detach", [
            "product_ids" => $products->pluck("id")
        ]);

        $this->assertCount(0,$this->user->load("cart")->cart->products);

    }

    /** @test */
    public function 사용자는_유효기간이_지난_상품을_일괄삭제할_수_있다()
    {
        $cart = Cart::factory([
            "user_id" => $this->user->id
        ])->create();

        $products = Product::factory()->count(11)->create();

        foreach($products as $product){
            $cart->products()->attach($product->id, [
                "can_order" => false
            ]);
        }

        $this->delete("/carts/detachExpired");

        $this->assertCount(0, $this->user->load("cart")->cart->products);

    }

    /** @test */
    public function 배송비는_프론트에서_일자별로_표시를_해주되_주문할_때_배송비를_계산하기_최소배송무료금액_이상이면_무료로_표시하기()
    {

    }

}
