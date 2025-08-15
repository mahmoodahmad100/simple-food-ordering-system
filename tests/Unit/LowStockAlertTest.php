<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Mail\LowStockAlert;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LowStockAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_low_stock_alert_constructor()
    {
        $ingredient = Ingredient::create([
            'name' => 'Beef',
            'total_amount' => 1000,
            'current_amount' => 100,
        ]);

        $mail = new LowStockAlert($ingredient);

        $this->assertInstanceOf(LowStockAlert::class, $mail);
        $this->assertEquals($ingredient, $mail->ingredient);
    }

    public function test_low_stock_alert_envelope()
    {
        $ingredient = Ingredient::create([
            'name' => 'Chicken',
            'total_amount' => 500,
            'current_amount' => 50,
        ]);

        $mail = new LowStockAlert($ingredient);
        $envelope = $mail->envelope();

        $this->assertEquals('Low Stock Alert', $envelope->subject);
    }

    public function test_low_stock_alert_content()
    {
        $ingredient = Ingredient::create([
            'name' => 'Lettuce',
            'total_amount' => 100,
            'current_amount' => 10,
        ]);

        $mail = new LowStockAlert($ingredient);
        $content = $mail->content();

        $this->assertEquals('emails.low-stock-alert', $content->view);
    }

    public function test_low_stock_alert_attachments()
    {
        $ingredient = Ingredient::create([
            'name' => 'Tomato',
            'total_amount' => 200,
            'current_amount' => 20,
        ]);

        $mail = new LowStockAlert($ingredient);
        $attachments = $mail->attachments();

        $this->assertIsArray($attachments);
        $this->assertEmpty($attachments);
    }

    public function test_low_stock_alert_implements_should_queue()
    {
        $ingredient = Ingredient::create([
            'name' => 'Onion',
            'total_amount' => 300,
            'current_amount' => 30,
        ]);

        $mail = new LowStockAlert($ingredient);

        $this->assertInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class, $mail);
    }
}
