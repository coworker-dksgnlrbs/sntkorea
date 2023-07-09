<?php

namespace Tests\Feature\Pages;

use App\Models\Allergy;
use App\Models\DeliveryAmount;
use App\Models\DeliveryDate;
use App\Models\DeliveryDuration;
use App\Models\Diet;
use App\Models\Holidate;
use App\Models\Holiday;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\DeliveryAmountFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DietProductCreateTest extends TestCase
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
    public function 사용중인_식단생성기가_없다면_새로운_식단생성기를_생성한다()
    {
        $product = Product::factory()->create([
            "isDiet" => true
        ]);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $product->id
        ]);

        $this->assertCount(1, auth()->user()->diets);
    }

    /** @test */
    public function 상용화되지_않은_식단생성기는_하나밖에_가질_수_없다()
    {
        $product = Product::factory()->create([
            "isDiet" => true
        ]);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $product->id
        ]);

        $this->assertCount(1, auth()->user()->diets);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $product->id
        ]);

        $this->assertCount(1, auth()->user()->diets);
    }

    /** @test */
    public function 사용중인_식단생성기가_있다면_식단생성기를_새로_생성하지_않는다()
    {
        $product = Product::factory()->create([
            "isDiet" => false
        ]);

        // 사용중인 식단생성기 없을 경우
        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $product->id
        ]);

        $diet = auth()->user()->diets()->first();

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $product->id
        ]);

        $this->assertCount(1, auth()->user()->diets);
        $this->assertNotEquals($diet->id, auth()->user()->diets()->first()->id);


        // 사용중인 식단생성기가 있을 경우
        $this->get("/dietProducts/create/" . $product->id);

        $diet = auth()->user()->diets()->first();

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $product->id,
            "diet_id" => $diet->id
        ]);

        $this->assertCount(1, auth()->user()->diets);
        $this->assertEquals($diet->id, auth()->user()->diets()->first()->id);
    }

    /** @test */
    public function 식단생성페이지에서_해당_식단에_포함된_상품목록을_조회할_수_있다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $includeProducts = Product::factory()->count(12)->create();

        foreach ($includeProducts as $product) {
            $dietProduct->products()->attach($product->id, [
                "count" => rand(0, 10),
                "assignment_at" => Carbon::now()
            ]);
        }

        $excludeProducts = Product::factory()->count(12)->create();

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProduct->id
        ])->assertInertia(function ($page) use ($includeProducts) {
            $items = $page->toArray()["props"]["products"]["data"];

            $this->assertCount(count($includeProducts), $items);
        });
    }

    /** @test */
    public function 식단생성페이지에서_이번달을_포함한_두달치_식단일정을_조회할_수_있다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $includeDurationProducts = Product::factory()->count(12)->create();

        foreach ($includeDurationProducts as $product) {
            $assignmentAt = Carbon::now()->addWeeks(rand(0,7));

            $dietProduct->products()->attach($product->id, [
                "count" => rand(0, 10),
                "assignment_at" => $assignmentAt
            ]);
        }

        $excludeDurationProducts = Product::factory()->count(12)->create();

        foreach ($excludeDurationProducts as $product) {
            $assignmentAt = Carbon::now()->addWeeks(rand(9,20));

            $dietProduct->products()->attach($product->id, [
                "count" => rand(0, 10),
                "assignment_at" => $assignmentAt
            ]);
        }

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id
        ])->assertInertia(function ($page) use ($includeDurationProducts) {
            $items = $page->toArray()["props"]["products"]["data"];

            $this->assertCount(count($includeDurationProducts), $items);
        });
    }

    /** @test */
    public function 배송시작일을_설정하면_해당_날짜주로부터_두달치_식단일정을_조회할_수_있다()
    {
        Product::truncate();

        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $deliveryStartedAt = Carbon::now()->addDays(2);

        $includeProducts = Product::factory()->count(12)->create();

        foreach ($includeProducts as $product) {
            $assignmentAt = Carbon::make($deliveryStartedAt)->addWeeks(8)->endOfWeek()->subDays(rand(0,50));

            $dietProduct->products()->attach($product->id, [
                "count" => rand(0, 10),
                "assignment_at" => $assignmentAt
            ]);
        }

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertInertia(function ($page) use ($includeProducts) {
            $items = $page->toArray()["props"]["products"]["data"];

            $this->assertCount(count($includeProducts), $items);
        });
    }

    /** @test */
    public function 배송시작일은_최소_2일뒤부터_가능하다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $deliveryStartedAt = Carbon::now();

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertStatus(422);

        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $deliveryStartedAt = Carbon::now()->addDay();

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertStatus(422);
    }

    /** @test */
    public function 배송시작일은_최대_2주를_넘길_수_없다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $deliveryStartedAt = Carbon::now()->addWeeks(2)->addDay()->startOfDay();

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertStatus(422);

        $deliveryStartedAt = Carbon::now()->addWeeks(2)->endOfDay();

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertInertia();

    }

    /** @test */
    public function 식단생성페이지에서_배송수량_목록을_조회할_수_있다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $deliveryAmounts = DeliveryAmount::factory()->count(10)->create([
            "product_id" => $dietProduct->id
        ]);

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProduct->id
        ])->assertInertia(function ($page) use ($deliveryAmounts) {
                $items = $page->toArray()["props"]["deliveryAmounts"]["data"];

                $this->assertCount(count($deliveryAmounts), $items);
            });;
    }

    /** @test */
    public function 식단생성페이지에서_배송수량을_통해_배송요일_목록을_조회할_수_있다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $deliveryAmount = DeliveryAmount::factory()->create([
            "product_id" => $dietProduct->id
        ]);

        $deliveryDates = DeliveryDate::factory()->count(10)->create([
            "delivery_amount_id" => $deliveryAmount->id
        ]);

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProduct->id
        ])->assertInertia(function ($page) use ($deliveryDates) {
                $items = $page->toArray()["props"]["deliveryAmounts"]["data"];

                $this->assertCount(count($deliveryDates), $items[0]["deliveryDates"]);
            });;
    }

    /** @test */
    public function 식단생성페이지에서_배송기간_목록을_조회할_수_있다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $deliveryAmount = DeliveryAmount::factory()->create([
            "product_id" => $dietProduct->id
        ]);

        $deliveryDate = DeliveryDate::factory()->create([
            "delivery_amount_id" => $deliveryAmount->id
        ]);

        $deliveryDurations = DeliveryDuration::factory()->count(10)->create([
            "delivery_date_id" => $deliveryDate->id
        ]);

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProduct->id
        ])->assertInertia(function ($page) use($deliveryDurations){
                $deliveryAmounts = $page->toArray()["props"]["deliveryAmounts"]["data"];

                $items = $deliveryAmounts[0]["deliveryDates"][0]["deliveryDurations"];

                $this->assertCount(count($deliveryDurations), $items);
        });
    }

    /** @test */
    public function 식단생성페이지에서_알레르기_목록을_조회할_수_있다()
    {
        $allergies = Allergy::factory()->count(10)->create();

        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProduct->id
        ])->assertInertia(function ($page) use ($allergies) {
                $items = $page->toArray()["props"]["allergies"]["data"];

                $this->assertCount(count($allergies), $items);
            });;
    }

    /** @test */
    public function 식단생성페이지에서_식단목록을_조회할_수_있다()
    {
        $dietProducts = Product::factory()->count(8)->create([
            "isDiet" => true
        ]);;

        $products = Product::factory()->count(12)->create();

        foreach ($products as $product) {
            $dietProducts[0]->products()->attach($product->id, [
                "count" => rand(0, 10),
                "assignment_at" => Carbon::now()
            ]);
        }

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProducts[0]->id
        ])->assertInertia(function ($page) use ($dietProducts) {
            $items = $page->toArray()["props"]["dietProducts"]["data"];

            $this->assertCount(count($dietProducts), $items);
        });;
    }

    /** @test */
    public function 식단생성페이지에서_고정배송휴무요일을_조회할_수_있다()
    {
        $holiday = Holiday::factory()->create();

        $dietProduct = Product::factory()->create(["isDiet" => true]);

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProduct->id
        ])
            ->assertInertia(function ($page) {
                $item = $page->toArray()["props"]["holiday"];

                $this->assertNotNull($item);
            });
    }

    /** @test */
    public function 식단생성페이지에서_지정배송휴무일_목록을_조회할_수_있다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);;

        $holidates = Holidate::factory()->count(12)->create();

        $this->json("get", "/dietProducts/create", [
            "diet_product_id" => $dietProduct->id
        ])
            ->assertInertia(function ($page) use ($holidates) {
                $items = $page->toArray()["props"]["holidates"]["data"];

                $this->assertCount(count($holidates), $items);
            });;
    }

    /** @test */
    public function 식단주문정보를_모두_입력하면_식단과_연관된_회차상품이_자동생성된다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $countPerDay = 2; // 하루에 상품 몇개 배정되는지

        // 2달치 식단 만들기
        $dietProduct = $this->createDietPlan($dietProduct, $countPerDay);

        // 배송수량, 배송요일, 기간, 첫수령일 설정
        $deliveryAmount = DeliveryAmount::factory()->create([
            "product_id" => $dietProduct->id,
            "count" => 4
        ]);

        // 배송요일
        $deliveryDate = DeliveryDate::factory()->create([
            "delivery_amount_id" => $deliveryAmount->id,
            "mon" => true,
            "wed" => true
        ]);

        // 몇주치
        $deliveryDuration = DeliveryDuration::factory()->create([
            "week" => 3
        ]);

        // 배송시작일
        $deliveryStartedAt = $this->getDeliveryStartedAt($deliveryDate);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertInertia(function ($page) use ($dietProduct, $countPerDay, $deliveryAmount, $deliveryDate, $deliveryDuration){
            $roundProducts = $page->toArray()["props"]["roundProducts"]["data"];
            $diet = $page->toArray()["props"]["diet"]["data"];

            $products = [];

            foreach($roundProducts as $roundProduct){
                $roundProduct = Product::find($roundProduct["id"]);

                $products = array_merge($products, $roundProduct->roundIncludeProducts->toArray());
            }

            // 개수가 정확한지
            $this->assertCount($countPerDay * $deliveryAmount->count * $deliveryDuration->week, $products);

            // 회차가 정확한지
            $this->assertCount($deliveryDuration->week * count($deliveryDate->toDates()), $roundProducts);
        });
    }

    // 식단상품 가격은 1일치 가격을 입력해야돼
    /** @test */
    public function 식단주문정보를_모두_입력하면_식단가격이_계산된다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true,
            "price" => 850
        ]);

        $countPerDay = 2; // 하루에 상품 몇개 배정되는지

        // 2달치 식단 만들기
        $dietProduct = $this->createDietPlan($dietProduct, $countPerDay);

        // 배송수량, 배송요일, 기간, 첫수령일 설정
        $deliveryAmount = DeliveryAmount::factory()->create([
            "product_id" => $dietProduct->id,
            "count" => 4
        ]);

        // 배송요일
        $deliveryDate = DeliveryDate::factory()->create([
            "delivery_amount_id" => $deliveryAmount->id,
            "mon" => true,
            "wed" => true
        ]);

        // 몇주치
        $deliveryDuration = DeliveryDuration::factory()->create([
            "week" => 3,
            "discount_ratio" => 5
        ]);

        // 배송시작일
        $deliveryStartedAt = $this->getDeliveryStartedAt($deliveryDate);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertInertia(function ($page) use ($dietProduct, $countPerDay, $deliveryAmount, $deliveryDate, $deliveryDuration){
            $diet = $page->toArray()["props"]["diet"]["data"];

            // 식단 가격이 정확한지(n일치 * n주치 * 할인률)
            $originPrice = $dietProduct->price * $deliveryAmount->count * $deliveryDuration->week;

            $salePrice = floor($originPrice * ($deliveryDuration->discount_ratio / 100));

            $totalPrice = $originPrice - $salePrice;

            $this->assertEquals($diet["price"], $totalPrice);
        });
    }

    /** @test */
    public function 식단주문정보를_모두_입력하면_회차상품목록을_조회할_수_있다()
    {

    }

    /** @test */
    public function 회차_배정일이_배송휴일이라면_배송가능한_이전_회차와_동일한_배송일로_회차상품이_생성된다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $countPerDay = 2; // 하루에 상품 몇개 배정되는지

        // 2달치 식단 만들기
        $dietProduct = $this->createDietPlan($dietProduct, $countPerDay);

        // 배송수량, 배송요일, 기간, 첫수령일 설정
        $deliveryAmount = DeliveryAmount::factory()->create([
            "product_id" => $dietProduct->id,
            "count" => 4
        ]);

        // 배송요일
        $deliveryDate = DeliveryDate::factory()->create([
            "delivery_amount_id" => $deliveryAmount->id,
            "mon" => true,
            "wed" => true
        ]);

        // 몇주치
        $deliveryDuration = DeliveryDuration::factory()->create([
            "week" => 2
        ]);

        // 배송시작일
        $deliveryStartedAt = $this->getDeliveryStartedAt($deliveryDate);


        // 1. 회차배정일이 휴일
        Holidate::factory()->create([
            "closed_at" => Carbon::make($deliveryStartedAt)->addWeek()
        ]);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertInertia(function ($page) use ($countPerDay, $deliveryAmount, $deliveryDate, $deliveryDuration){
            $roundProducts = $page->toArray()["props"]["roundProducts"]["data"];

            $this->assertEquals($roundProducts[1]["delivery_at"], $roundProducts[2]["delivery_at"]);
        });
    }

    /** @test */
    public function 첫_수령일과_배송요일은_다를_수_없다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $countPerDay = 2; // 하루에 상품 몇개 배정되는지

        // 2달치 식단 만들기
        $dietProduct = $this->createDietPlan($dietProduct, $countPerDay);

        // 배송수량, 배송요일, 기간, 첫수령일 설정
        $deliveryAmount = DeliveryAmount::factory()->create([
            "product_id" => $dietProduct->id,
            "count" => 4
        ]);

        // 배송요일
        $deliveryDate = DeliveryDate::factory()->create([
            "delivery_amount_id" => $deliveryAmount->id,
            "mon" => true,
        ]);

        // 몇주치
        $deliveryDuration = DeliveryDuration::factory()->create([
            "week" => 3
        ]);

        // 배송시작일
        $deliveryStartedAt = $this->getDeliveryStartedAt($deliveryDate)->addDay();

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertStatus(302);
    }

    /** @test */
    public function 첫_수령일로_휴무일을_선택할_수_없다()
    {
        $dietProduct = Product::factory()->create([
            "isDiet" => true
        ]);

        $countPerDay = 2; // 하루에 상품 몇개 배정되는지

        // 2달치 식단 만들기
        $dietProduct = $this->createDietPlan($dietProduct, $countPerDay);

        // 배송수량, 배송요일, 기간, 첫수령일 설정
        $deliveryAmount = DeliveryAmount::factory()->create([
            "product_id" => $dietProduct->id,
            "count" => 4
        ]);

        // 배송요일
        $deliveryDate = DeliveryDate::factory()->create([
            "delivery_amount_id" => $deliveryAmount->id,
            "mon" => true,
        ]);

        // 몇주치
        $deliveryDuration = DeliveryDuration::factory()->create([
            "week" => 3
        ]);

        // 배송시작일
        $deliveryStartedAt = $this->getDeliveryStartedAt($deliveryDate);


        // 월요일(배송시작일 요일) 휴무
        $holiday = Holiday::factory()->create([
            "mon" => true
        ]);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertStatus(302);


        // 월요일(배송시작일 요일) 휴무 아님 && 화요일 휴무
        $holiday->update([
            "mon" => false,
            "tue" => true
        ]);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertStatus(200);


        // 배송시작일이 지정휴무일
        $holidate = Holidate::factory()->create([
            "closed_at" => $deliveryStartedAt
        ]);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertStatus(302);


        // 배송시작일이 지정휴무일 아님
        $holidate->delete();

        $holidate = Holidate::factory()->create([
            "closed_at" => Carbon::make($deliveryStartedAt)->addWeek()
        ]);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertStatus(200);
    }


    /** @test */
    public function 회차상품의_개별상품수를_수정할_수_있다()
    {
        $roundProduct = $this->createRoundProduct();

        $roundIncludeProduct = $roundProduct->roundIncludeProducts()->first();

        $originCount = $roundIncludeProduct->pivot->count;


        $this->json("patch","/roundIncludeProducts", [
            "round_product_id" => $roundProduct->id,
            "product_id" => $roundIncludeProduct->id,
            "count" => $originCount + 1
        ]);

        $roundProduct->load("roundIncludeProducts");

        $roundIncludeProduct = $roundProduct->roundIncludeProducts()->find($roundIncludeProduct->id);

        $this->assertEquals($roundIncludeProduct->pivot->count, $originCount + 1);
    }

    // 월요일 단호박미음인데 들깨국 id 전달한다던지
    /** @test */
    public function 회차상품은_본래_구성_외의_상품을_가질_수_없다()
    {
        $roundProduct = $this->createRoundProduct();

        $roundIncludeProduct = $roundProduct->roundIncludeProducts()->first();
        $roundExcludeProduct = Product::factory()->create();

        $originCount = $roundIncludeProduct->pivot->count;

        $this->json("patch","/roundIncludeProducts", [
            "round_product_id" => $roundProduct->id,
            "product_id" => $roundExcludeProduct->id,
            "count" => $originCount + 1
        ])->assertStatus(302);
    }

    /** @test */
    public function 알레르기정보를_입력하면_회차상품의_구성상품이_해당_알레르기상품을_제외한_상품들로만_수정된다()
    {
        // 기본세팅
        $dietProduct = Product::factory()->create(["isDiet" => true]);
        $countPerDay = 2; // 하루에 상품 몇개 배정되는지
        $dietProduct = $this->createDietPlan($dietProduct, $countPerDay);
        $deliveryAmount = DeliveryAmount::factory()->create(["product_id" => $dietProduct->id, "count" => 4]);
        $deliveryDate = DeliveryDate::factory()->create(["delivery_amount_id" => $deliveryAmount->id, "mon" => true, "wed" => true]);
        $deliveryDuration = DeliveryDuration::factory()->create(["week" => 3]);
        $deliveryStartedAt = $this->getDeliveryStartedAt($deliveryDate);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt
        ]);

        // 알레르기
        $allergy = Allergy::factory()->create();

        $roundProducts = Product::where("diet_id", "!=", null)->get();

        $allergyTargetRound = $roundProducts[0];
        $allergyTargetRoundIncludeProduct =  $allergyTargetRound->roundIncludeProducts()->first();
        $allergyTargetRoundIncludeProduct->allergies()->attach($allergy);

        $originTotal = 0;

        foreach($roundProducts as $roundProduct){
            $roundIncludeProducts = $roundProduct->roundIncludeProducts;

            foreach($roundIncludeProducts as $product){
                $originTotal += $product->pivot->count;
            }
        }

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt,
            "allergy_ids" => [$allergy->id]
        ]);

        $total = 0;
        $allergyProductsCount = 0;

        $roundProducts = Product::where("diet_id", "!=", null)->get();

        foreach($roundProducts as $roundProduct){
            $roundIncludeProducts = $roundProduct->roundIncludeProducts;

            foreach($roundIncludeProducts as $roundIncludeProduct){
                if($roundIncludeProduct->id == $allergyTargetRoundIncludeProduct->id)
                    $allergyProductsCount += $roundIncludeProduct->pivot->count;

                $total += $roundIncludeProduct->pivot->count;
            }
        }

        // 알레르기 상품 개수가 0개가 됐는지
        $this->assertEquals(0, $allergyProductsCount);

        // 총 식단구성상품수가 이전과 일치한지
        $this->assertEquals($total, $originTotal);
    }

    /** @test */
    public function 회차상품이_생성된_식단만_장바구니에_담을_수_있다()
    {
        $dietProduct = Product::factory()->create(["isDiet" => true]);
        $countPerDay = 2; // 하루에 상품 몇개 배정되는지
        $dietProduct = $this->createDietPlan($dietProduct, $countPerDay);
        $deliveryAmount = DeliveryAmount::factory()->create(["product_id" => $dietProduct->id, "count" => 4]);
        $deliveryDate = DeliveryDate::factory()->create(["delivery_amount_id" => $deliveryAmount->id, "mon" => true, "wed" => true]);
        $deliveryDuration = DeliveryDuration::factory()->create(["week" => 3]);
        $deliveryStartedAt = $this->getDeliveryStartedAt($deliveryDate);

        $emptyDiet = Diet::factory()->create([
            "user_id" => $this->user->id
        ]);

        $this->post("/carts", [
            "isDiet" => 1,
            "diet_id" => $emptyDiet->id
        ]);

        $this->user->load("cart");

        $this->assertCount(0, $this->user->cart->products);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertInertia(function($page){
            $diet = $page->toArray()["props"]["diet"]["data"];

            $this->post("/carts", [
                "isDiet" => 1,
                "diet_id" => $diet["id"]
            ]);

            $this->user->load("cart");

            $diet = Diet::find($diet["id"]);

            $this->assertCount($diet->products()->count(), auth()->user()->cart->products);
        });
    }

    /** @test */
    public function 구성상품수가_회차상품_구성상품수와_일치해야_장바구니에_담을_수_있다()
    {
        // 기본세팅
        $dietProduct = Product::factory()->create(["isDiet" => true]);
        $countPerDay = 2; // 하루에 상품 몇개 배정되는지
        $dietProduct = $this->createDietPlan($dietProduct, $countPerDay);
        $deliveryAmount = DeliveryAmount::factory()->create(["product_id" => $dietProduct->id, "count" => 4]);
        $deliveryDate = DeliveryDate::factory()->create(["delivery_amount_id" => $deliveryAmount->id, "mon" => true, "wed" => true]);
        $deliveryDuration = DeliveryDuration::factory()->create(["week" => 3]);
        $deliveryStartedAt = $this->getDeliveryStartedAt($deliveryDate);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertInertia(function($page){
            $roundProducts = $page->toArray()["props"]["roundProducts"]["data"];

            $diet = $page->toArray()["props"]["diet"]["data"];

            $this->json("patch", "/roundIncludeProducts", [
                "round_product_id" => $roundProducts[0]["id"],
                "product_id" => $roundProducts[0]["roundIncludeProducts"][0]["id"],
                "count" => 0
            ]);

            $this->post("/carts", [
                "isDiet" => 1,
                "diet_id" => $diet["id"]
            ]);

            $this->user->load("cart");

            $diet = Diet::find($diet["id"]);

            $this->assertCount(0, auth()->user()->cart->products);
        });

    }

    /** @test */
    public function 식단상품을_장바구니에_담으면_식단생성기는_상용화된다()
    {
        // 기본세팅
        $dietProduct = Product::factory()->create(["isDiet" => true]);
        $countPerDay = 2; // 하루에 상품 몇개 배정되는지
        $dietProduct = $this->createDietPlan($dietProduct, $countPerDay);
        $deliveryAmount = DeliveryAmount::factory()->create(["product_id" => $dietProduct->id, "count" => 4]);
        $deliveryDate = DeliveryDate::factory()->create(["delivery_amount_id" => $deliveryAmount->id, "mon" => true, "wed" => true]);
        $deliveryDuration = DeliveryDuration::factory()->create(["week" => 3]);
        $deliveryStartedAt = $this->getDeliveryStartedAt($deliveryDate);

        $this->json("get","/dietProducts/create", [
            "diet_product_id" => $dietProduct->id,
            "delivery_amount_id" => $deliveryAmount->id,
            "delivery_duration_id" => $deliveryDuration->id,
            "delivery_date_id" => $deliveryDate->id,
            "delivery_started_at" => $deliveryStartedAt
        ])->assertInertia(function($page){
            $roundProducts = $page->toArray()["props"]["roundProducts"]["data"];

            $diet = $page->toArray()["props"]["diet"]["data"];

            $this->post("/carts", [
                "isDiet" => 1,
                "diet_id" => $diet["id"]
            ]);

            $diet = Diet::find($diet["id"]);

            $this->assertEquals(1, $diet->commercialization);
        });
    }

    /** @test */
    public function 관리자사이트_테스트에_1주치_배송수량하고나서_배송요일_생성할_때_일자_사이_간격_계산해서_배송요일_막_못들게_막는_로직_넣어야돼()
    {
        // 예를 들어 1주 배송수량 5일치로 해놓고, [월 토 일] 설정하면 월요일날 5일치 다 나가버리고 토,일은 배송 못함. 이런거 계산해서 못만들게 해야돼
    }

    /** @test */
    public function 관리자사이트에서_배송수량_1일_이상_7일_미만_유효성검사필요()
    {

    }

    public function getDeliveryStartedAt($deliveryDate, $startedAt = null, $date = null)
    {
        if($date == null)
            $date = $this->getDate($deliveryDate);

        if(!$startedAt)
            $startedAt = Carbon::now()->addDays(2);

        if($startedAt->dayOfWeek == $date) {
            $startedAt->dayOfWeek;

            return $startedAt;
        }

        return $this->getDeliveryStartedAt($deliveryDate, $startedAt->addDay(), $date);
    }

    public function getDate($deliveryDate)
    {
        if($deliveryDate->mon)
            return Carbon::MONDAY;

        if($deliveryDate->tue)
            return Carbon::TUESDAY;

        if($deliveryDate->wed)
            return Carbon::WEDNESDAY;

        if($deliveryDate->thur)
            return Carbon::THURSDAY;

        if($deliveryDate->fri)
            return Carbon::FRIDAY;

        if($deliveryDate->sat)
            return Carbon::SATURDAY;

        if($deliveryDate->sun)
            return Carbon::SUNDAY;

        return $this->getDate($deliveryDate);
    }

    public function createDietPlan($dietProduct, $countPerDay)
    {
        // 2달치 식단 만들기
        for($m=0; $m < 2; $m++){

            $endOfMonth = Carbon::now()->addMonthsNoOverflow($m)->endOfMonth()->day;

            for($i=1; $i <= $endOfMonth; $i++){
                for($j=0; $j < $countPerDay; $j++){
                    $product = Product::factory()->create();

                    $dietProduct->products()->attach($product->id, [
                        "count" => 1,
                        "assignment_at" => Carbon::now()->addMonthsNoOverflow($m)->setDay($i)
                    ]);
                }
            }
        }

        return $dietProduct;
    }

    public function createRoundProduct()
    {
        $diet = Diet::factory()->create([
            "user_id" => $this->user->id
        ]);

        $roundProduct = Product::factory()->create([
            "diet_id" => $diet->id,
        ]);

        $roundIncludeProducts = Product::factory()->count(4)->create();

        foreach($roundIncludeProducts as $roundIncludeProduct){
            $roundProduct->roundIncludeProducts()->attach($roundIncludeProduct, [
                "count" => 1,
                "assignment_at" => Carbon::now() // 나중에 바꿔
            ]);
        }

        return $roundProduct;
    }
}
