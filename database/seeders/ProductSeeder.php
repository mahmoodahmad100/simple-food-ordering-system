<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $beef = Ingredient::firstOrCreate([
            'name' => 'Beef',
        ], [
            'total_amount'   => 20000,
            'current_amount' => 20000
        ]);

        $cheese = Ingredient::firstOrCreate([
            'name' => 'Cheese',
        ], [
            'total_amount'   => 5000,
            'current_amount' => 5000
        ]);

        $onion = Ingredient::firstOrCreate([
            'name' => 'Onion',
        ], [
            'total_amount'   => 1000,
            'current_amount' => 1000
        ]);

        $burger_product = Product::firstOrCreate(['name' => 'Burger']);

        $burger_product->ingredients()->sync([
            $beef->id   => ['amount' => 150],
            $cheese->id => ['amount' => 30],
            $onion->id  => ['amount' => 20],
        ]);
    }
}
