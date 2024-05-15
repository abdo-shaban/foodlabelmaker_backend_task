<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StorePromoCodeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_valid_store_promo_code_request()
    {

        // Arrange
        $user    = User::factory()->create();
        $payload = [
            'code'  => 'testCode',
            'type'  => 'percentage',
            'value' => 10,
        ];

        // Act
        $response = $this->actingAs($user)->json('POST', 'api/promo-codes', $payload);

        // Assert
        $response->assertStatus(201);
        $response->assertJsonPath('data.code', 'testCode');
        $response->assertJsonPath('data.type', 'percentage');
        $response->assertJsonPath('data.value', 10);

        // check database
        $this->assertDatabaseHas('promo_codes', [
            'code'  => 'testCode',
            'type'  => 'percentage',
            'value' => 10,
        ]);
    }
}
