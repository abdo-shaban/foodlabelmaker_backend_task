<?php

namespace App\Http\Controllers;

use App\Data\StorePromoCodeDTOData;
use App\Http\Requests\StorePromoCodeRequest;
use App\Http\Resources\PromoCodeResource;
use App\Services\PromoCodeService;

class PromoCodeController extends Controller
{
    public function __construct(public PromoCodeService $promoCodeService)
    {
    }

    public function store(StorePromoCodeRequest $request)
    {
        $promoCode = $this->promoCodeService->storePromoCode(StorePromoCodeDTOData::from($request->validated()));
        return new PromoCodeResource($promoCode);
    }
}
