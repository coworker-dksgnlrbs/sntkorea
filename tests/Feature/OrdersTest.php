<?php

namespace Tests\Feature;

use App\Enums\CouponTypeDiscount;
use App\Enums\OrderState;
use App\Enums\OutgoingState;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Delivery;
use App\Models\Diet;
use App\Models\Order;
use App\Models\PayMethod;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $cart;

    protected $payMethods;

    protected $deliveries;

    protected $coupons;

    protected $form;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->cart = Cart::factory()->create([
            "user_id" => $this->user->id
        ]);

        $this->products = Product::factory()->count(10)->create();

        $this->cart->products()->attach($this->products->pluck("id"));

        $this->payMethods = PayMethod::factory()->count(3)->create();

        $this->deliveries = Delivery::factory()->count(3)->create([
            "user_id" => $this->user->id
        ]);

        $this->coupons = Coupon::factory()->count(3)->create();

        $this->user->coupons()->attach($this->coupons->pluck("id"), [
            "expired_at" => Carbon::now()->addDays(2)
        ]);

        $this->form = [
            "product_ids" => $this->products->pluck("id")->toArray(),
            "user_contact" => $this->user->order_contact,
            "user_name" => $this->user->order_name,

            "pay_method_id" => $this->payMethods->first()->id,
            "memo" => "떡볶이 많이",

            "delivery_title" => $this->deliveries->first()->title,
            "delivery_name" => $this->deliveries->first()->name,
            "delivery_contact" => $this->deliveries->first()->contact,
            "delivery_address" => $this->deliveries->first()->address,
            "delivery_address_detail" => $this->deliveries->first()->address_detail,
            "delivery_address_zipcode" => $this->deliveries->first()->address_zipcode,
            "delivery_memo" => $this->deliveries->first()->memo,
            "delivery_default" => false,

            "delivery_at" => Carbon::now()->addDays(7),

            "coupon_id" => null,

            "point_use" => 0,

            "agree" => 1
        ];

        $this->actingAs($this->user);
    }

    /** @test */
    public function 한번에_여러상품을_주문할_수_있다()
    {
        $this->post("/orders", $this->form);

        $order = $this->user->orders()->first();

        $this->assertCount(count($this->products), $order->products);
    }

    /** @test */
    function 한번에_최대_99개의_상품만_주문할_수_있다()
    {
        // 100개
        $products = Product::factory()->count(100)->create();

        $this->cart->products()->attach($products->pluck("id"));

        $this->form["product_ids"] = $products->pluck("id")->toArray();

        $this->post("/orders", $this->form);

        $order = $this->user->orders()->first();

        $this->assertNull($order);


        // 99개
        $products = Product::factory()->count(99)->create();

        $this->form["product_ids"] = $products->pluck("id")->toArray();

        $this->cart->products()->attach($products->pluck("id"));

        $this->post("/orders", $this->form);

        $order = $this->user->orders()->first();

        $this->assertCount(count($products), $order->products);
    }

    /** @test */
    function 주문을_생성하면_출고목록이_생성된다()
    {
        $diet = Diet::factory()->create([
            "user_id" => $this->user->id
        ]);

        $singleProducts = Product::factory()->count(10)->create();

        $roundProducts = Product::factory()->count(11)->create([
            "diet_id" => $diet->id
        ]);

        $this->form["product_ids"] = array_merge($singleProducts->pluck("id")->toArray(), $roundProducts->pluck("id")->toArray());

        $this->cart->products()->attach($singleProducts->pluck("id"));
        $this->cart->products()->attach($roundProducts->pluck("id"));

        $this->post("/orders", $this->form);

        $order = $this->user->orders()->first();

        $this->assertCount(count($singleProducts) + count($roundProducts), $order->products);

        // 단품은 묶음배송 + 회차상품은 회차별로 출고목록 생성
        $this->assertCount(1 + count($roundProducts), $order->outgoings);
    }

    /** @test */
    public function 단품에_관련된_출고목록의_배송예정일은_주문시_설정한_배송예정일로_설정된다()
    {
        $singleProducts = Product::factory()->count(10)->create();

        $this->form["product_ids"] = $singleProducts->pluck("id")->toArray();

        $this->cart->products()->attach($singleProducts->pluck("id"));

        $this->post("/orders", $this->form);

        $order = $this->user->orders()->first();

        foreach($order->outgoings as $outgoing){
            $this->assertEquals(Carbon::make($this->form["delivery_at"])->format("Y-m-d"), Carbon::make($outgoing->delivery_at)->format("Y-m-d"));
        }
    }

    /** @test */
    public function 식단에_관련된_출고목록의_배송예정일은_회치상품의_배송예정일로_설정된다()
    {
        $diet = Diet::factory()->create();

        $roundProducts = Product::factory()->count(10)->create([
            "diet_id" => $diet->id
        ]);

        $this->form["product_ids"] = $roundProducts->pluck("id")->toArray();

        $this->cart->products()->attach($roundProducts->pluck("id"));

        $this->post("/orders", $this->form);

        $order = $this->user->orders()->first();

        foreach($order->outgoings as $outgoing){
            $this->assertNotEquals(Carbon::make($this->form["delivery_at"])->format("Y-m-d"), Carbon::make($outgoing->delivery_at)->format("Y-m-d"));
        }
    }

    /** @test */
    public function 전에_배송한적_없던_배송정보로_주문했다면_배송지목록에_추가된다()
    {
        Delivery::truncate();

        $this->post("/orders", $this->form);

        $this->assertCount(1, $this->user->load("deliveries")->deliveries);

        // 같은주소일 시 추가 x
        $this->post("/orders", $this->form);

        $this->assertCount(1, $this->user->load("deliveries")->deliveries);


        // 다른주소일 시 추가
        $this->form["delivery_address"] = "테스트";

        $this->post("/orders", $this->form);

        $this->assertCount(2, $this->user->load("deliveries")->deliveries);
    }

    /** @test */
    public function 기본배송지를_설정할_수_있다()
    {
        $this->form["delivery_default"] = 1;

        $this->post("/orders", $this->form);

        $delivery = $this->user->load("deliveries")->deliveries()
            ->where("title", $this->form["delivery_title"])
            ->where("name", $this->form["delivery_name"])
            ->where("address", $this->form["delivery_address"])
            ->where("address_detail", $this->form["delivery_address_detail"])
            ->first();

        $this->assertEquals(1, $delivery->default);

        $otherDeliveries = $this->user->deliveries()->where("id", "!=", $delivery->id)->get();

        foreach($otherDeliveries as $otherDelivery){
            $this->assertEquals(0, $otherDelivery->default);
        }
    }

    /** @test */
    public function 구매동의를_해야만_주문을_진행할_수_있다()
    {
        $this->form["agree"] = 0;

        $this->post("/orders", $this->form);

        $this->assertCount(0, $this->user->load("orders")->orders);
    }

    /** @test */
    public function 장바구니에_담겨있지않던_상품은_주문되지_않는다()
    {
        $excludeProducts = Product::factory()->count(10)->create();

        $this->form["product_ids"] = array_merge($this->form["product_ids"], $excludeProducts->pluck("id")->toArray());

        $this->post("/orders", $this->form);

        $this->assertCount(count($this->products), $this->user->load("orders")->orders()->first()->products);
    }

    /** @test */
    public function 한_상품을_여러개_주문할_수_있다()
    {
        $count = 4;

        $product = Product::factory()->create();

        $this->cart->products()->attach($product, [
            "count" => $count
        ]);

        $this->form["product_ids"] = [$product->id];

        $this->post("/orders", $this->form);

        $product = $this->user->load("orders")->orders()->first()->products()->first();

        $this->assertEquals($count, $product->pivot->count);
    }

    /** @test */
    public function 주문서가_성공상태로_수정되면_관련_상품들은_장바구니에서_삭제된다()
    {
        $this->post("/orders", $this->form);

        $this->assertCount(count($this->products), $this->cart->load("products")->products);

        $order = Order::first();

        $order->update([
            "state" => OrderState::SUCCESS
        ]);

        $this->assertCount(0, $this->cart->load("products")->products);
    }

    /** @test */
    public function 주문서가_성공상태로_수정되면_사용적립금은_차감되고_혜택적립금은_추가된다()
    {
        $this->user->update([
            "point" => 1
        ]);

        $prevUser = $this->user;

        $this->form["point_use"] = $this->user->point;

        $this->post("/orders", $this->form);

        $this->assertCount(count($this->products), $this->cart->load("products")->products);

        $order = Order::first();

        $order->update([
            "state" => OrderState::SUCCESS
        ]);

        $user = User::find($this->user->id);

        $this->assertEquals($prevUser->point + $order->point_give - $order->point_use, $user->point);

        $this->assertEquals($order->price_total - $this->form["point_use"] + $order->delivery_price, $order->price_real);
    }

    /** @test */
    public function 주문서가_성공상태로_수정되면_사용한_쿠폰이_사용처리된다()
    {
        $coupon = Coupon::factory()->create([
            "price_min" => 1,
            "price_discount" => 1
        ]);

        $this->user->coupons()->attach($coupon, [
            "expired_at" => Carbon::now()->addDays(7)
        ]);

        $this->form["coupon_id"] = $coupon->id;

        $this->post("/orders", $this->form);

        $order = Order::first();

        $order->update([
            "state" => OrderState::SUCCESS
        ]);

        $coupon = $this->user->load("coupons")->coupons()->find($coupon->id);

        $this->assertEquals(1, $coupon->pivot->used);

        $this->assertEquals($order->price_total - $coupon->price_discount + $order->delivery_price, $order->price_real);
    }

    /** @test */
    public function 주문서가_성공상태로_수정되면_출고목록은_주문접수_상태가_된다()
    {
        $this->post("/orders", $this->form);

        $order = Order::first();

        $order->update([
            "state" => OrderState::SUCCESS
        ]);

        $outgoings = $order->load("outgoings")->outgoings;

        foreach($outgoings as $outgoing){
            $this->assertEquals(OutgoingState::WAIT, $outgoing->state);
        }
    }

    /** @test */
    public function 주문서가_가상계좌입금대기상태로_수정되면_사용한_쿠폰이_사용처리된다()
    {
        $coupon = Coupon::factory()->create([
            "price_min" => 1,
            "price_discount" => 1
        ]);

        $this->user->coupons()->attach($coupon, [
            "expired_at" => Carbon::now()->addDays(7)
        ]);

        $this->form["coupon_id"] = $coupon->id;

        $this->post("/orders", $this->form);

        $order = Order::first();

        $order->update([
            "state" => OrderState::WAIT
        ]);

        $coupon = $this->user->load("coupons")->coupons()->find($coupon->id);

        $this->assertEquals(1, $coupon->pivot->used);

        $this->assertEquals($order->price_total - $coupon->price_discount + $order->delivery_price, $order->price_real);
    }

    /** @test */
    public function 주문서가_가상계좌입금대기상태로_수정되면_관련_상품들은_장바구니에서_삭제된다()
    {
        $this->post("/orders", $this->form);

        $this->assertCount(count($this->products), $this->cart->load("products")->products);

        $order = Order::first();

        $order->update([
            "state" => OrderState::WAIT
        ]);

        $this->assertCount(0, $this->cart->load("products")->products);
    }

    /** @test */
    public function 주문서가_가상계좌입금대기상태로_수정되면_사용적립금은_차감되지만_적립금은_추가되지_않는다()
    {
        $this->user->update([
            "point" => 1
        ]);

        $prevUser = $this->user;

        $this->form["point_use"] = $this->user->point;

        $this->post("/orders", $this->form);

        $this->assertCount(count($this->products), $this->cart->load("products")->products);

        $order = Order::first();

        $order->update([
            "state" => OrderState::WAIT
        ]);

        $user = User::find($this->user->id);

        $this->assertEquals($prevUser->point - $order->point_use, $user->point);

        $this->assertEquals($order->price_total - $this->form["point_use"] + $order->delivery_price, $order->price_real);
    }

    /** @test */
    public function 주문서가_가상계좌입금대기상태로_수정되도_출고목록은_주문접수_상태가_되지_않는다()
    {
        $this->post("/orders", $this->form);

        $order = Order::first();

        $order->update([
            "state" => OrderState::WAIT
        ]);

        $outgoings = $order->load("outgoings")->outgoings;

        foreach($outgoings as $outgoing){
            $this->assertEquals(OutgoingState::FAIL, $outgoing->state);
        }
    }

    /** @test */
    public function 가상계좌입금대기상태였던_주문은_입금기한이_지나면_실패처리된다()
    {

    }

    /** @test */
    public function 가상계좌_주문건은_입금완료처리_됐을때_적립금이_추가된다()
    {
        $this->user->update([
            "point" => 1
        ]);

        $prevUser = $this->user;

        $this->form["point_use"] = $this->user->point;

        $this->post("/orders", $this->form);

        $this->assertCount(count($this->products), $this->cart->load("products")->products);

        $order = Order::first();

        $order->update([
            "state" => OrderState::WAIT
        ]);

        $order->update([
            "state" => OrderState::SUCCESS
        ]);


        $user = User::find($this->user->id);

        $this->assertEquals($prevUser->point + $order->point_give - $order->point_use, $user->point);

        $this->assertEquals($order->price_total - $this->form["point_use"] + $order->delivery_price, $order->price_real);
    }

    /** @test */
    public function 실패처리된_가상계좌_주문건의_사용적립금은_반환된다()
    {
        $this->user->update([
            "point" => 1
        ]);

        $prevUser = $this->user;

        $this->form["point_use"] = $this->user->point;

        $this->post("/orders", $this->form);

        $this->assertCount(count($this->products), $this->cart->load("products")->products);

        $order = Order::first();

        $order->update([
            "state" => OrderState::WAIT
        ]);

        $order->update([
            "state" => OrderState::FAIL
        ]);

        $user = User::find($this->user->id);

        $this->assertEquals($prevUser->point, $user->point);
    }

    /** @test */
    public function 실패처리된_가상계좌_주문건의_쿠폰은_미사용_처리된다()
    {
        $coupon = Coupon::factory()->create([
            "price_min" => 1,
            "price_discount" => 1
        ]);

        $this->user->coupons()->attach($coupon, [
            "expired_at" => Carbon::now()->addDays(7)
        ]);

        $this->form["coupon_id"] = $coupon->id;

        $this->post("/orders", $this->form);

        $order = Order::first();

        $order->update([
            "state" => OrderState::WAIT
        ]);

        $order->update([
            "state" => OrderState::FAIL
        ]);

        $coupon = $this->user->load("coupons")->coupons()->find($coupon->id);

        $this->assertEquals(0, $coupon->pivot->used);
    }


    /** @test */
    public function 주문자_연락처는_자신의_연락처여야만_한다()
    {
        // 연락처 바꾸고싶으면 자신의 연락처정보를 폰번호 인증을 통해 바꿔야함
        $this->form["user_contact"] = "123123123";

        $this->post("/orders", $this->form);

        $this->assertCount(0, $this->user->load("orders")->orders);
    }

    /** @test */
    public function 배송불가상품은_주문되지_않는다()
    {
        $cantOrderProducts = Product::factory()->count(10)->create();

        $this->cart->products()->attach($cantOrderProducts->pluck("id"), [
            "can_order" => false
        ]);

        $this->form["product_ids"] = array_merge($this->form["product_ids"], $cantOrderProducts->pluck("id")->toArray());

        $this->post("/orders", $this->form);

        $this->assertCount(count($this->products), $this->user->load("orders")->orders()->first()->products);
    }

    /** @test */
    function 상품정보에_기반하여_주문금액이_계산한다()
    {
        $this->user->update(["point" => 354]);

        $diets = [
            Diet::factory()->create([
                "user_id" => $this->user->id,
                "price" => 12300
            ]),
            Diet::factory()->create([
                "user_id" => $this->user->id,
                "price" => 5980
            ]),
        ];

        $products = [
            Product::factory()->create([
                "price" => 1580
            ]),
            Product::factory()->create([
                "price" => 3222
            ]),
            Product::factory()->create([
                "price" => 5000
            ]),

            Product::factory()->create([
                "diet_id" => $diets[0]->id,
            ]),
            Product::factory()->create([
                "diet_id" => $diets[0]->id,
            ]),
            Product::factory()->create([
                "diet_id" => $diets[0]->id,
            ]),

            Product::factory()->create([
                "diet_id" => $diets[1]->id,
            ]),
            Product::factory()->create([
                "diet_id" => $diets[1]->id,
            ]),
        ];

        $this->cart->products()->attach($products[0], [
            "count" => 2
        ]);

        $this->cart->products()->attach($products[1], [
            "count" => 1
        ]);

        $this->cart->products()->attach($products[2], [
            "count" => 3
        ]);

        // 식단은 회차상품 한개 카운트가 올라가면 나머지도 같이 올라가야함
        $this->cart->products()->attach($products[3], [
            "count" => 3
        ]);

        $this->cart->products()->attach($products[4], [
            "count" => 3
        ]);

        $this->cart->products()->attach($products[5], [
            "count" => 3
        ]);

        $this->cart->products()->attach([$products[6]->id, $products[7]->id]);

        $coupon = Coupon::factory()->create([
            "type_discount" => CouponTypeDiscount::RATIO,
            "price_min" => 1000,
            "ratio_discount" => 10
        ]);

        $this->user->coupons()->attach($coupon, [
            "expired_at" => Carbon::now()->addDay()
        ]);

        $this->form["product_ids"] = [];

        $this->form["coupon_id"] = $coupon->id;

        $this->form["point_use"] = $this->user->point;

        foreach($products as $product){
            $this->form["product_ids"][] = $product->id;
        }

        $this->post("/orders", $this->form);

        $order = $this->user->load("orders")->orders()->first();

        //$this->assertEquals(39662,$order->price_total);
        // 64860
        // 63908
        $this->assertEquals(64262,$order->price_total);
        //$this->assertEquals(38710,$order->price_real);
        $this->assertEquals(57482,$order->price_real);
    }

    /** @test */
    function 유효하지_않은_결제수단으로는_주문을_진행할_수_없다()
    {
        $this->form["pay_method_id"] = 0;

        $this->post("/orders", $this->form);

        $this->assertNull($this->user->load("orders")->orders()->first());
    }

    /** @test */
    public function 주문내역에_단품이_있고_최소배송무료금액_미만이라면_기본배송비가_붙는다()
    {
        // 최소배송무료금액 미만일 경우
        $products = Product::factory()->count(3)->create([
            "price" => 1000
        ]);

        $this->cart->products()->attach($products->pluck("id"));

        $this->form["product_ids"] = $products->pluck("id")->toArray();

        $this->post("/orders", $this->form);

        $order = $this->user->load("orders")->orders()->first();

        $this->assertEquals($products->sum("price"),$order->price_total);

        $this->assertEquals(config("setting.delivery_price"),$order->delivery_price);

        $this->assertEquals($order->price_total + $order->delivery_price,$order->price_real);
    }

    /** @test */
    public function 주문내역에_단품이_있고_최소배송무료금액_이상이라면_기본배송비가_붙지_않는다()
    {
        $products = Product::factory()->count(3)->create([
            "price" => config("setting.delivery_min_discount_price")
        ]);

        $this->cart->products()->attach($products->pluck("id"));

        $this->form["product_ids"] = $products->pluck("id")->toArray();

        $this->post("/orders", $this->form);

        $order = $this->user->load("orders")->orders()->orderBy("created_at", "desc")->first();

        $this->assertEquals($products->sum("price"),$order->price_total);

        $this->assertEquals(0,$order->delivery_price);

        $this->assertEquals($order->price_total,$order->price_real);
    }

    /** @test */
    public function 식단만_주문했을_경우_최소배송무료금액_미만이라도_기본배송비가_붙지_않는다()
    {
        $diet = Diet::factory()->create([
            "user_id" => $this->user->id
        ]);

        $products = Product::factory()->count(3)->create([
            "price" => 1,
            "diet_id" => $diet->id
        ]);

        $this->cart->products()->attach($products->pluck("id"));

        $this->form["product_ids"] = $products->pluck("id")->toArray();

        $this->post("/orders", $this->form);

        $order = $this->user->load("orders")->orders()->orderBy("created_at", "desc")->first();

        $this->assertEquals(0,$order->delivery_price);

        $this->assertEquals($order->price_total,$order->price_real);
    }


    /** @test */
    public function 적립금은_실제결제금액을_초과하여_사용할_수_없다()
    {
        $this->user->update([
            "point" => 10000
        ]);

        $products = Product::factory()->count(3)->create([
            "price" => 1
        ]);

        $this->cart->products()->attach($products->pluck("id"));

        $this->form["product_ids"] = $products->pluck("id")->toArray();
        $this->form["point_use"] = $this->user->point;

        $this->post("/orders", $this->form);

        $this->assertCount(0, $this->user->load("orders")->orders);
    }


    /** @test */
    public function 적립금은_사용자가_보유한_적립금을_초과하여_사용할_수_없다()
    {
        $this->user->update([
            "point" => 100
        ]);

        $products = Product::factory()->count(3)->create([
            "price" => 5000
        ]);

        $this->cart->products()->attach($products->pluck("id"));

        $this->form["product_ids"] = $products->pluck("id")->toArray();
        $this->form["point_use"] = $this->user->point * 2;

        $this->post("/orders", $this->form);

        $this->assertCount(0, $this->user->load("orders")->orders);
    }


    /** @test */
    public function 보유하지_않은_쿠폰을_사용할_수_없다()
    {
        $coupon = Coupon::factory()->create();

        $this->form["coupon_id"] = $coupon->id;

        $this->post("/orders", $this->form);

        $this->assertCount(0, $this->user->load("orders")->orders);
    }


    /** @test */
    public function 최소주문금액을_만족하지_않은_쿠폰은_사용할_수_없다()
    {
        $coupon = Coupon::factory()->create([
            "price_min" => 3000
        ]);

        $this->user->coupons()->attach($coupon, [
            "expired_at" => Carbon::now()->addDay()
        ]);

        $product = Product::factory()->create([
            "price" => $coupon->price_min - 1
        ]);

        $this->user->cart->products()->attach($product);

        $this->form["coupon_id"] = $coupon->id;

        $this->form["product_ids"] = [$product->id];

        $this->post("/orders", $this->form);

        $this->assertCount(0, $this->user->load("orders")->orders);
    }


    /** @test */
    public function 유효기간이_지난_쿠폰은_사용할_수_없다()
    {
        $coupon = Coupon::factory()->create([
            "price_min" => 3000
        ]);

        $this->user->coupons()->attach($coupon, [
            "expired_at" => Carbon::now()->subDay()
        ]);

        $product = Product::factory()->create([
            "price" => $coupon->price_min - 1
        ]);

        $this->user->cart->products()->attach($product);

        $this->form["coupon_id"] = $coupon->id;

        $this->form["product_ids"] = [$product->id];

        $this->post("/orders", $this->form);

        $this->assertCount(0, $this->user->load("orders")->orders);
    }


    /** @test */
    public function 사용했던_쿠폰은_사용할_수_없다()
    {
        $coupon = Coupon::factory()->create([
            "price_min" => 3000
        ]);

        $this->user->coupons()->attach($coupon, [
            "used" => 1,
            "expired_at" => Carbon::now()->subDay()
        ]);

        $product = Product::factory()->create([
            "price" => $coupon->price_min - 1
        ]);

        $this->user->cart->products()->attach($product);

        $this->form["coupon_id"] = $coupon->id;

        $this->form["product_ids"] = [$product->id];

        $this->post("/orders", $this->form);

        $this->assertCount(0, $this->user->load("orders")->orders);
    }


    /** @test */
    function 실패상태_주문을_제외한_자신의_주문내역을_조회할_수_있다()
    {
        $other = User::factory()->create();

        $myFailOrders = Order::factory()->count(3)->create([
            "state" => OrderState::FAIL,
            "user_id" => $this->user->id
        ]);

        $myWaitOrders = Order::factory()->count(3)->create([
            "state" => OrderState::WAIT,
            "user_id" => $this->user->id
        ]);

        $mySuccessOrders = Order::factory()->count(4)->create([
            "state" => OrderState::SUCCESS,
            "user_id" => $this->user->id
        ]);

        $otherSuccessOrders = Order::factory()->count(4)->create([
            "user_id" => $other->id,
            "state" => OrderState::SUCCESS
        ]);

        $this->get("/orders")->assertInertia(function($page) use($myWaitOrders, $mySuccessOrders){
            $items = $page->toArray()["props"]["orders"]["data"];

            $this->assertCount(count($myWaitOrders) + count($mySuccessOrders), $items);
        });
    }

    // 반품으로 갈거 ============================================
    // 상품준비로 넘어가면서부터는 환불신청 안됨
    function 구매자는_주문접수상태_상품만_주문취소할_수_있다()
    {

    }

    function 구매자는_다른_사람의_상품을_주문취소할_수_없다()
    {

    }

    public function 주문취소시_사용했던_적립금은_반환된다()
    {

    }

    public function 주문취소시_사용했던_쿠폰은_미사용_상태로_전환된다()
    {

    }

    public function 주문취소시_관련_출고목록은_삭제된다()
    {

    }

    public function 결제취소가_불가능한_상품은_계좌환불_진행중_상태로_전환된다()
    {

    }

    public function 지금_예약배송_최대_4주로_설정되어있는데_2주로_바꿔야돼()
    {

    }

    /** @test */
    public function 가상계좌및_웹훅_포함해서_결제_후_알림메세지_넣어야돼()
    {

    }

    /** @test */
    public function 주문취소안될경우계좌환불관련테스트케이스도만들어야돼()
    {

    }

    /** @test */
    public function 주문접수상태_상품만_상품준비상태로_전환할_수_있도록_관리자세팅해야하고_운송장등록할_데이터_엑셀_추출할때도_마찬가지임()
    {

    }

    /** @test */
    public function 관리자에서_오늘_나가면_안되는_배송예정일_출고상품을_준비완료처리하려고할_때도_막아야돼()
    {
        // 이번주 금요일날 예약배송 희망했는데, 월요일날 상품준비 찍어버리면 안되겠지? 수요일날 찍어야겠지(2일차이 나는지? 계산하는식으로 해야할듯)
        // 아니면 렌즈나 필터기능을 통해서 오늘 나가야 하는 출고목록을 만들 수 있게하던지(순차출고 대비해서 나갔어야했는데 아직 못나간것도 포함하게 해야됨)
    }

    /** @test */
    public function 관리자에서_오늘_나가야할_상품_렌즈나_필터링을_할_수_있게하자()
    {
        // 가상계좌같은건 결제 자체를 3일뒤에 할 수도 있어
        // 그렇기때문에 출고일자가 오늘인거 뿐만 아니라 그전 일자에 안나간거도 포함해서 오늘 나가야할 상품 목록에 나오게끔 해야돼
    }
}
