<?php

namespace Tests\Feature;

use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckValidityPromoCodeTest extends TestCase
{
    use RefreshDatabase;

    // Returns a JSON response with price, promo_code_discounted_amount, and final_price.
    public function test_check_validity_returns_json_response()
    {
        // Arrange
        $user = User::factory()->create();
        $promoCode = PromoCode::factory()->create();
        $payload =  [
            'promo_code' => $promoCode->code,
            'price' => 100.00,
        ];


        // Act
        $response = $this->actingAs($user)->json('GET', 'api/promo-codes/validate', $payload);

        // Assert
        $response->assertOk();
        $response->assertJson([
            'price' => 100.00,
            'promo_code_discounted_amount' => 10.00,
            'final_price' => 90.00,
        ]);
    }


    public function test_check_validity_throws_message_exception_if_promo_code_invalid()
    {
        // Arrange
        $user = User::factory()->create();
        $payload =  [
            'promo_code' => 'testWrongCode',
            'price' => 100.00,
        ];


        // Act
        $response = $this->actingAs($user)->json('GET', 'api/promo-codes/validate', $payload);

        // Assert
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Invalid promo code'
        ]);
    }


    // Attaches the user to the promo code if the promo code is valid.
    public function test_check_validity_attaches_user_to_promo_code_if_valid()
    {
        // Arrange
        $user = User::factory()->create();
        $promoCode = PromoCode::factory()->create(['user_ids' =>  [$user->id]]);
        $payload =  [
            'promo_code' => $promoCode->code,
            'price' => 100.00,
        ];

        // Act
        $response = $this->actingAs($user)->json('GET', 'api/promo-codes/validate', $payload);

        // Assert
        $this->assertDatabaseHas('user_promo_code', [
            'promo_code_id' => $promoCode->id,
            'user_id' => $user->id,
        ]);
    }

    // Throws a MessageException with status code 404 if the promo code is expired.
    public function test_check_validity_throws_exception_if_promo_code_expired()
    {
        // Arrange
        $promoCode = PromoCode::factory()->create([
            'expiry_date' => now()->subDay(),
        ]);
        $payload =  [
            'promo_code' => $promoCode->code,
            'price' => 100.00,
        ];

        // Act
        $user = User::factory()->create();
        $response = $this->actingAs($user)->json('GET', 'api/promo-codes/validate', $payload);

        // Assert
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Promo code expired'
        ]);
    }

    // Throws a MessageException with status code 404 if the promo code has reached its maximum usage count.
    public function test_check_validity_throws_exception_if_promo_code_reached_max_usage_count()
    {
        // Arrange
        $promoCode = PromoCode::factory()->create([
            'max_usage_count' => 1,
        ]);
        $user = User::factory()->create();
        $user->promoCodes()->attach($promoCode->id);
        $payload =  [
            'promo_code' => $promoCode->code,
            'price' => 100.00,
        ];

        // Act
        $response = $this->actingAs($user)->json('GET', 'api/promo-codes/validate', $payload);

        // Assert
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Promo code reached maximum usage count'
        ]);
    }

    // Throws a MessageException with status code 404 if the promo code has reached its maximum usage per user.
    public function test_check_validity_throws_exception_if_promo_code_reached_max_usage_per_user()
    {
        // Arrange
        $promoCode = PromoCode::factory()->create([
            'max_usage_count' => 10,
            'max_usage_per_user' => 1,
        ]);
        $user = User::factory()->create();
        $user->promoCodes()->attach($promoCode->id);
        $payload =  [
            'promo_code' => $promoCode->code,
            'price' => 100.00,
        ];

        // Act
        $response = $this->actingAs($user)->json('GET', 'api/promo-codes/validate', $payload);

        // Assert
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Promo code reached maximum usage per user'
        ]);
    }

    // Throws a MessageException with status code 404 if the promo code is not valid for the user.
    public function test_check_validity_throws_exception_if_promo_code_is_not_valid_for_user()
    {
        // Arrange
        $promoCode = PromoCode::factory()->create(['user_ids' =>  [9999]]);
        $user = User::factory()->create();
        $payload =  [
            'promo_code' => $promoCode->code,
            'price' => 100.00,
        ];

        // Act
        $response = $this->actingAs($user)->json('GET', 'api/promo-codes/validate', $payload);

        // Assert
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User is not eligible to use this promo code'
        ]);
    }

}
