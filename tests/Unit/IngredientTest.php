<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IngredientTest extends TestCase
{
    use RefreshDatabase;

    public function test_ingredient_belongs_to_many_products()
    {
        $ingredient = Ingredient::create([
            'name' => 'Beef',
            'total_amount' => 1000,
            'current_amount' => 500,
        ]);

        $product1 = Product::create(['name' => 'Burger']);
        $product2 = Product::create(['name' => 'Steak']);

        $ingredient->products()->attach($product1->id, ['amount' => 100]);
        $ingredient->products()->attach($product2->id, ['amount' => 200]);

        $this->assertCount(2, $ingredient->products);
        $this->assertTrue($ingredient->products->contains($product1));
        $this->assertTrue($ingredient->products->contains($product2));
        $this->assertEquals(100, $ingredient->products->first()->pivot->amount);
    }

    public function test_ingredient_fillable_attributes()
    {
        $ingredient = Ingredient::create([
            'name' => 'Chicken',
            'total_amount' => 500,
            'current_amount' => 300,
        ]);

        $this->assertEquals('Chicken', $ingredient->name);
        $this->assertEquals(500, $ingredient->total_amount);
        $this->assertEquals(300, $ingredient->current_amount);
    }
}
