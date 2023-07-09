<?php

namespace Database\Seeders;

use App\Enums\OutgoingState;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Outgoing;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MassTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::factory()->count(2)->create();

        $outgoings = Outgoing::factory()->count(5000)->create([
            "state" => OutgoingState::READY
        ]);

        $order = Order::factory()->create();

        foreach($outgoings as $outgoing){
            foreach($products as $product){
                OrderProduct::create([
                    "outgoing_id" => $outgoing->id,
                    "product_id" => $product->id,
                    "order_id" => $order->id,
                    "product_title" => $product->title,
                    "product_price" => $product->price,
                    "count" => rand(1,4)
                ]);
            }
        }
    }
}
