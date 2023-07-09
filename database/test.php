/** @test */
public function 단품의_예약배송일은_최소_2일뒤만_가능하다()
{
$product = Product::factory()->create();

$this->post("/carts", [
"isDiet" => 0,
"count" => 1,
"product_id" => $product->id,
"delivery_at" => Carbon::now()->format("Y-m-d")
]);

$cart = $this->user->load("cart")->cart;

$this->assertCount(0, $cart->products);

$this->post("/carts", [
"isDiet" => 0,
"count" => 1,
"product_id" => $product->id,
"delivery_at" => Carbon::now()->addDay()
]);

$cart = $this->user->load("cart")->cart;

$this->assertCount(0, $cart->products);


$this->post("/carts", [
"isDiet" => 0,
"count" => 1,
"product_id" => $product->id,
"delivery_at" => Carbon::now()->addDays(2)->format("Y-m-d")
]);

$cart = $this->user->load("cart")->cart;

$this->assertCount(1, $cart->products);
}

/** @test */
public function 단품의_예약배송일은_최대_1달을_넘을_수_없다()
{
$product = Product::factory()->create();

$this->post("/carts", [
"isDiet" => 0,
"count" => 1,
"product_id" => $product->id,
"delivery_at" => Carbon::now()->addMonth()
]);

$cart = $this->user->load("cart")->cart;

$this->assertCount(0, $cart->products);

$this->post("/carts", [
"isDiet" => 0,
"count" => 1,
"product_id" => $product->id,
"delivery_at" => Carbon::now()->addMonth()->subDay()
]);

$cart = $this->user->load("cart")->cart;

$this->assertCount(1, $cart->products);
}

/** @test */
public function 단품의_예약배송일은_배송휴일이_될_수_없다()
{
$product = Product::factory()->create();

$holydate = Holidate::factory()->create(["closed_at" => Carbon::now()->addWeek()]);

$this->post("/carts", [
"isDiet" => 0,
"count" => 1,
"product_id" => $product->id,
"delivery_at" => $holydate->closed_at
]);

$cart = $this->user->load("cart")->cart;

$this->assertCount(0, $cart->products);

$holiday = Holiday::factory()->create([
"wed" => true
]);

$deliveryAt = Carbon::now()->next("Wednesday");

$this->post("/carts", [
"isDiet" => 0,
"count" => 1,
"product_id" => $product->id,
"delivery_at" => $deliveryAt
]);

$cart = $this->user->load("cart")->cart;

$this->assertCount(0, $cart->products);
}

/** @test */
public function 장바구니상품_목록_조회시_장바구니에_담아놓은_예약배송일이_지난_단품은_주문불가_처리된다()
{
// 2일 내
$cart = Cart::factory()->create([
"user_id" => $this->user->id
]);

$expiredCartProduct = CartProduct::factory()->create([
"cart_id" => $cart->id,
"delivery_at" => Carbon::now(),
"can_order" => 1
]);

$this->get("/carts");

$expiredCartProduct = CartProduct::find($expiredCartProduct->id);

$this->assertEquals($expiredCartProduct->can_order, 0);


// 휴일
$holidate = Holidate::factory()->create([
"closed_at" => Carbon::now()->addWeek()
]);

$expiredCartProduct = CartProduct::factory()->create([
"cart_id" => $cart->id,
"delivery_at" => $holidate->closed_at,
"can_order" => 1
]);

$this->get("/carts");

$expiredCartProduct = CartProduct::find($expiredCartProduct->id);

$this->assertEquals($expiredCartProduct->can_order, 0);
}

/** @test */
public function 사용자는_단품의_예약배송일을_변경할_수_있다()
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

$cartProduct = CartProduct::where("product_id", $product->id)->where("cart_id", $cart->id)->first();

$changeDeliveryAt = Carbon::now()->addDays(11);

$this->json("patch", "/carts", [
"change_delivery_at" => $changeDeliveryAt,
"cart_product_id" => $cartProduct->id
]);


$cartProduct = $cart->getCartProducts($product->id, $changeDeliveryAt)->first();

$this->assertNotNull($cartProduct);

$prevCartProduct = auth()->user()->load("cart")->cart->getCartProducts($product->id, $prevDeliveryAt)->first();

$this->assertNull($prevCartProduct);

$this->assertCount(1, $cart->products);
}

/** @test */
public function 같은_단품을_다시_담더라도_예약배송일이_다르면_상품의_개수가_증가하지_않는다()
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

$this->post("/carts", [
"isDiet" => 0,
"product_id" => $product->id,
"count" => $count,
"delivery_at" => Carbon::now()->addDays(4)
]);

$this->post("/carts", [
"isDiet" => 0,
"product_id" => $product->id,
"count" => $count,
"delivery_at" => Carbon::now()->addDays(4)
]);

auth()->user()->load("cart");

$this->assertEquals(2, auth()->user()->cart->products()->count());

$this->assertEquals($count * 3,auth()->user()->cart->products()->sum("count"));
}

/** @test */
public function 사용자가_단품을_담으면_자동으로_예약배송일이_2일이상이자_배송휴무일이_아닌날로_설정된다()
{
$product = Product::factory()->create();

$count = 4;

// 2일 뒤가 배송휴무일이 아닌 경우
$this->post("/carts", [
"isDiet" => 0,
"product_id" => $product->id,
"count" => $count
]);

$cart = auth()->user()->load("cart")->cart;

$cartProduct = $cart->getCartProducts($product->id, Carbon::now()->addDays(2))->first();

$this->assertNotNull($cartProduct);

// 2일 뒤가 배송 휴무일이고 3일 뒤가 배송 휴무일이 아닌경우
$holidate = Holidate::factory()->create([
"closed_at" => Carbon::now()->addDays(2)
]);

$this->post("/carts", [
"isDiet" => 0,
"product_id" => $product->id,
"count" => $count
]);

$cartProduct = $cart->getCartProducts($product->id, Carbon::now()->addDays(3))->first();

$this->assertNotNull($cartProduct);

Holidate::factory()->create([
"closed_at" => Carbon::now()->addDays(3)
]);

Holidate::factory()->create([
"closed_at" => Carbon::now()->addDays(4)
]);

// 2일 뒤 ~ 4일 뒤까지 배송 휴무일인 경우
$this->post("/carts", [
"isDiet" => 0,
"product_id" => $product->id,
"count" => $count
]);

$cartProduct = $cart->getCartProducts($product->id, Carbon::now()->addDays(5))->first();

$this->assertNotNull($cartProduct);
}
