<?php

namespace App\Services;

use App\Data\CheckValidityPromoCodeDTOData;
use App\Data\StorePromoCodeDTOData;
use App\Exceptions\MessageException;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Support\Str;

class PromoCodeService
{

    public function storePromoCode(StorePromoCodeDTOData $storePromoCodeDTOData): PromoCode
    {
        $promoCode       = new PromoCode();
        $promoCode->code = $storePromoCodeDTOData->code;
        if (! $storePromoCodeDTOData->code) {
            $promoCode->code = $this->generateUniqueCode();
        }
        $promoCode->expiry_date        = $storePromoCodeDTOData->expiry_date;
        $promoCode->max_usage_count    = $storePromoCodeDTOData->max_usage_count;
        $promoCode->max_usage_per_user = $storePromoCodeDTOData->max_usage_per_user;
        $promoCode->user_ids           = $storePromoCodeDTOData->user_ids;
        $promoCode->type               = $storePromoCodeDTOData->type;
        $promoCode->value              = $storePromoCodeDTOData->value;
        $promoCode->save();

        return $promoCode;
    }

    private function generateUniqueCode($length = 8): string
    {
        $code = Str::random($length);

        // Check if the generated code already exists in the database
        while (PromoCode::where('code', $code)->exists()) {
            $code = Str::random($length);
        }

        return $code;
    }

    public function checkValidity(CheckValidityPromoCodeDTOData $checkValidityPromoCodeDTOData):array
    {
        $promoCode = $this->promoCodeIsExists($checkValidityPromoCodeDTOData->promo_code);
        if (! $promoCode) {
            throw new MessageException('Invalid promo code', 404);
        }

        if ($promoCode->expiry_date < now()->toDateString()) {
            throw new MessageException('Promo code expired', 404);
        }

        if ($this->checkReachedMaxUsageCount($promoCode)) {
            throw new MessageException('Promo code reached maximum usage count', 404);
        }

        if ($this->checkReachedMaxUsagePerUser($promoCode, auth()->user())) {
            throw new MessageException('Promo code reached maximum usage per user', 404);
        }

        if (! $this->checkUserEligibleToUse($promoCode, auth()->user())) {
            throw new MessageException('User is not eligible to use this promo code', 404);
        }

        $price = $checkValidityPromoCodeDTOData->price;
        $promo_code_discounted_amount = $this->calculatePromoCodeDiscount($promoCode, $price);
        $final_price                  = $price - $promo_code_discounted_amount;

        // Update usage count and attach user to promo code
        $user = auth()->user();
        $user->promoCodes()->attach($promoCode->id);

        return [
            'price'                        => $price,
            'promo_code_discounted_amount' => $promo_code_discounted_amount,
            'final_price'                  => $final_price,
        ];


    }

    private function promoCodeIsExists(string $promo_code): ?PromoCode
    {
        return PromoCode::where('code', $promo_code)->first();
    }

    private function checkReachedMaxUsageCount(PromoCode $promoCode): bool
    {
        if (! $promoCode->max_usage_count) {
            return false;
        }

        if ($promoCode->max_usage_count <= $promoCode->loadCount('users')->users_count) {
            return true;
        }

        return false;
    }

    private function checkReachedMaxUsagePerUser(PromoCode $promoCode, User $user): bool
    {
        if (! $promoCode->max_usage_per_user) {
            return false;
        }

        if ($promoCode->max_usage_per_user <= $this->userUsageCount($promoCode, $user)) {
            return true;
        }

        return false;
    }

    private function userUsageCount(PromoCode $promoCode, User $user)
    {
        return $promoCode->loadCount(['users' => function ($query) use ($user) {
            $query->where('users.id', $user->id);
        }])
            ->users_count;
    }

    private function checkUserEligibleToUse(PromoCode $promoCode, User $user)
    {
        if (! $promoCode->user_ids) {
            return true;
        }

        if (in_array($user->id, $promoCode->user_ids)) {
            return true;
        }

        return false;
    }

    private function calculatePromoCodeDiscount(PromoCode $promoCode, float $price)
    {
        if ($promoCode->type === 'percentage') {
            $promo_code_discounted_amount = ($price * ($promoCode->value / 100));
        }

        if ($promoCode->type === 'value') {
            $promo_code_discounted_amount = $promoCode->value;
        }

        if ($promo_code_discounted_amount > $price) {
            $promo_code_discounted_amount = $price;
        }
        return $promo_code_discounted_amount;
    }
}
