<?php

namespace Pages;

use App\Enums\CouponTypeDiscount;
use App\Enums\CouponTypeExpired;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Delivery;
use App\Models\PayMethod;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersCreateTest extends TestCase
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
            "expired_at" => Carbon::now()->addDays(8)
        ]);

        $this->form = [
            "product_ids" => $this->products->pluck("id"),
        ];

        $this->actingAs($this->user);
    }

    /** @test */
    public function 결제수단목록을_조회할_수_있다()
    {
        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ])->assertInertia(function ($page) {
            $items = $page->toArray()["props"]["payMethods"]["data"];

            $this->assertCount(count($this->payMethods), $items);
        });
    }

    /** @test */
    public function 배송주소지목록을_최근업데이트순으로_조회할_수_있다()
    {
        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ])->assertInertia(function ($page) {
            $items = $page->toArray()["props"]["deliveries"]["data"];

            $this->assertCount(count($this->deliveries), $items);
        });
    }

    /** @test */
    public function 자신의_배송주소지를_삭제할_수_있다()
    {
        Delivery::truncate();

        $other = User::factory()->create();

        $myDeliveries = Delivery::factory()->count(3)->create([
            "user_id" => $this->user->id
        ]);

        $otherDeliveries = Delivery::factory()->count(3)->create([
            "user_id" => $other->id
        ]);

        $this->delete("/deliveries/" . $myDeliveries[0]->id);

        $this->assertCount(count($myDeliveries) - 1, $this->user->load("deliveries")->deliveries);

        $this->delete("/deliveries/" . $otherDeliveries[0]->id);

        $this->assertCount(count($otherDeliveries), $other->load("deliveries")->deliveries);
    }

    /** @test */
    public function 기본배송지를_조회할_수_있다()
    {
        $defaultDelivery = Delivery::factory()->create([
            "user_id" => $this->user->id,
            "default" => true
        ]);

        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ])->assertInertia(function ($page) use ($defaultDelivery) {
            $item = $page->toArray()["props"]["defaultDelivery"]["data"];

            $this->assertEquals($defaultDelivery->id, $item["id"]);
        });
    }

    /** @test */
    public function 장바구니에_없는_상품은_상품목록에_포함되지_않는다()
    {
        $excludeProducts = Product::factory()->count(7)->create();

        $includeProducts = Product::factory()->count(12)->create();

        $this->cart->products()->attach($includeProducts->pluck("id"));

        $this->json("get", "/orders/create", [
            "product_ids" => $includeProducts->pluck("id")->merge($excludeProducts->pluck("id"))
        ])->assertInertia(function ($page) use ($includeProducts) {
            $items = $page->toArray()["props"]["products"]["data"];

            $this->assertCount(count($includeProducts), $items);
        });
    }

    /** @test */
    public function 배송불가상품은_상품목록에_포함되지_않는다()
    {
        $expiredIncludeProducts = Product::factory()->count(7)->create();

        $includeProducts = Product::factory()->count(12)->create();

        $this->cart->products()->attach($expiredIncludeProducts->pluck("id"), [
            "can_order" => false
        ]);

        $this->cart->products()->attach($includeProducts->pluck("id"));

        $this->json("get", "/orders/create", [
            "product_ids" => $includeProducts->pluck("id")->merge($expiredIncludeProducts->pluck("id"))
        ])->assertInertia(function ($page) use ($includeProducts) {
            $items = $page->toArray()["props"]["products"]["data"];

            $this->assertCount(count($includeProducts), $items);
        });
    }

    /** @test */
    public function 유효기간이_지나지_않고_사용하지_않은_쿠폰목록을_조회할_수_있다()
    {
        Coupon::truncate();

        $expiredCoupons = Coupon::factory()->count(3)->create();

        $usedCoupons = Coupon::factory()->count(5)->create();

        $validCoupons = Coupon::factory()->count(7)->create();

        $this->user->coupons()->attach($expiredCoupons->pluck("id"), [
            "expired_at" => Carbon::now()->subDay()
        ]);

        $this->user->coupons()->attach($usedCoupons->pluck("id"), [
            "used" => 1,
            "expired_at" => Carbon::now()->addDays(2)
        ]);

        $this->user->coupons()->attach($validCoupons->pluck("id"), [
            "expired_at" => Carbon::now()->addDays(2)
        ]);

        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ])->assertInertia(function ($page) use($validCoupons) {
            $items = $page->toArray()["props"]["coupons"]["data"];

            $this->assertCount(count($validCoupons), $items);
        });
    }

    /** @test */
    public function 발급일로부터_기간부여형_쿠폰은_자동_발급된다()
    {
        // 초기화
        Coupon::truncate();

        $this->user->coupons()->detach();

        $coupon = Coupon::factory()->create([
            "type_expired" => CouponTypeExpired::PERIOD,
            "open" => true,
        ]);

        $this->assertCount(0, $this->user->coupons);

        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ]);

        $this->assertCount(1, $this->user->load("coupons")->coupons);
    }

    /** @test */
    public function 발급일로부터_기간부여형_쿠폰은_발급일로부터_부여일자가_더해진_날짜로_만료일자가_계산된다()
    {
        // 초기화
        Coupon::truncate();

        $this->user->coupons()->detach();

        $coupon = Coupon::factory()->create([
            "type_expired" => CouponTypeExpired::PERIOD,
            "open" => true,
            "expiration_period" => 5
        ]);

        $this->assertCount(0, $this->user->coupons);

        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ]);

        $attachedCoupon = $this->user->load("coupons")->coupons()->first();

        $this->assertEquals(Carbon::now()->addDays($coupon->expiration_period)->format("Y-m-d"), Carbon::make($attachedCoupon->pivot->expired_at)->format("Y-m-d"));
    }

    /** @test */
    public function 정해진_만료일자형_쿠폰은_만료일자가_지나지_않았을_경우에만_자동_발급된다()
    {
        // 초기화
        Coupon::truncate();

        $this->user->coupons()->detach();

        $coupon = Coupon::factory()->create([
            "type_expired" => CouponTypeExpired::SPECIFIC,
            "open" => true,
            "expired_at" => Carbon::now()->subDay()
        ]);

        $this->assertCount(0, $this->user->coupons);

        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ]);

        $this->assertCount(0, $attachedCoupon = $this->user->load("coupons")->coupons);
    }

    /** @test */
    public function 정해진_만료일자형_쿠폰은_발급일과_상관없이_설정된_만료일자로_계산된다()
    {
        // 초기화
        Coupon::truncate();

        $this->user->coupons()->detach();

        $coupon = Coupon::factory()->create([
            "type_expired" => CouponTypeExpired::SPECIFIC,
            "open" => true,
            "expired_at" => Carbon::now()->addDays(3)
        ]);

        $this->assertCount(0, $this->user->coupons);

        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ]);

        $attachedCoupon = $this->user->load("coupons")->coupons()->first();

        $this->assertEquals(Carbon::make($coupon->expired_at)->format("Y-m-d"), Carbon::make($attachedCoupon->pivot->expired_at)->format("Y-m-d"));
    }

    /** @test */
    public function 동일한_사용할_수_있는_쿠폰이_없다면_중복허용쿠폰은_재발급된다()
    {
        // 초기화
        Coupon::truncate();

        $this->user->coupons()->detach();

        $coupon = Coupon::factory()->create([
            "open" => true,
            "duplicate" => true,
        ]);

        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ]);

        $this->assertCount(1, $this->user->load("coupons")->coupons);

        // 발급일로부터형 쿠폰 사용처리 후 재발급 시도(발급됨)
        foreach($this->user->coupons as $coupon){
            $this->user->coupons()->updateExistingPivot($coupon->id, ["used" => true]);
        };

        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ]);

        $this->assertCount(2, $this->user->load("coupons")->coupons);
    }

    /** @test */
    public function 동일한_사용할_수_있는_쿠폰이_없더라도_이미_기간이_만료된_특정일형_중복허용쿠폰은_재발급되지_않는다()
    {
        // 초기화
        Coupon::truncate();

        $this->user->coupons()->detach();

        // 발급일로부터형 쿠폰
        $coupon = Coupon::factory()->create([
            "type_expired" => CouponTypeExpired::SPECIFIC,
            "open" => true,
            "duplicate" => true,
            "expired_at" => Carbon::now()->addDays(2),
        ]);

        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ]);

        $this->assertCount(1, $this->user->load("coupons")->coupons);

        // 발급일로부터형 쿠폰 사용처리 후 재발급 시도 (but 만료기한 지나서 발급 안됨)
        foreach($this->user->coupons as $coupon){
            $this->user->coupons()->updateExistingPivot($coupon->id, ["used" => true]);
        };

        $coupon->update(["expired_at" => Carbon::now()->subDay()]);

        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ]);

        $this->assertCount(1, $this->user->load("coupons")->coupons);

    }

    /** @test */
    public function 중복허용된_쿠폰이더라도_사용_가능한_쿠폰이_남아있다면_재발급되지_않는다()
    {
        // 초기화
        Coupon::truncate();

        $this->user->coupons()->detach();

        $coupon = Coupon::factory()->create([
            "open" => true,
            "duplicate" => true,
        ]);

        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ]);

        $this->assertCount(1, $this->user->load("coupons")->coupons);

        // 사용할 수 있는 쿠폰이 남아있는 경우 (재발급 안됨)
        $this->json("get", "/orders/create", [
            "product_ids" => $this->products->pluck("id")
        ]);

        $this->assertCount(1, $this->user->load("coupons")->coupons);
    }

    /** @test */
    public function 배송비를_알_수_있다()
    {
        // 단품이 없다면 0원

        // 단품이 있다면 기본배송비

        // 최소배송무료금액 이상일 시 단품이 있어도 0원
    }

    /** @test */
    public function 주문쪽에_옮길건데_쿠폰할인이_적용되기전_가격이_최소할인적용금액을_넘었는지를_확인해서_쿠폰_적용해야됨()
    {

    }
}
