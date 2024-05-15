<?php

namespace App\Services;

use App\Data\StorePromoCodeDTOData;
use App\Data\UserLoginDTOData;
use App\Models\PromoCode;
use Illuminate\Support\Str;

class PromoCodeService
{

    public function storePromoCode(StorePromoCodeDTOData $storePromoCodeDTOData):PromoCode
    {
        $promoCode = new PromoCode();
        $promoCode->code = $storePromoCodeDTOData->code;
        if (!$storePromoCodeDTOData->code) {
            $promoCode->code = $this->generateUniqueCode();
        }
        $promoCode->expiry_date = $storePromoCodeDTOData->expiry_date;
        $promoCode->max_usage_count = $storePromoCodeDTOData->max_usage_count;
        $promoCode->max_usage_per_user = $storePromoCodeDTOData->max_usage_per_user;
        $promoCode->user_ids = $storePromoCodeDTOData->user_ids;
        $promoCode->type = $storePromoCodeDTOData->type;
        $promoCode->value = $storePromoCodeDTOData->value;
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
}
