<?php

namespace Tests\Feature;

use App\Models\Basket_item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function all_products_can_be_viewed()
    {
        $this->withoutExceptionHandling();

        $attributes = [
                'name' => 'Pioneer DJ Mixer',
                'price' => 699,
        ];

        $this->assertDatabaseHas('products', $attributes);

        $this->get('/api/products')->assertSee($attributes);
    }

    /** @test */
    public function a_product_can_be_added_to_basket()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'product_id' => $this->faker->numberBetween(1, 5)
        ];

        $this->post('/api/basket', $attributes)->assertStatus(200);

        $this->assertDatabaseHas('basket_items', $attributes);

        $this->get('/api/basket')->assertSee($attributes['product_id']);
    }

    /** @test */
    public function a_product_can_be_removed_from_basket()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'product_id' => $this->faker->numberBetween(1, 5)
        ];

        $this->post('/api/basket', $attributes)->assertStatus(200);

        $item = Basket_item::latest()->first()->toArray();

        $this->delete('/api/basket/'.$item['id'])->assertStatus(200);

        $this->assertDatabaseMissing('basket_items', $item);
    }

//    /** @test */
//    public function a_product_can_be_removed_from_basket_and_added_to_removed_items()
//    {
//
//    }
}
