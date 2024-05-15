<?php

namespace App\Http\Controllers;

use App\Data\CheckValidityPromoCodeDTOData;
use App\Data\StorePromoCodeDTOData;
use App\Http\Requests\CheckValidityPromoCodeRequest;
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

    public function checkValidity(CheckValidityPromoCodeRequest $request)
    {
        $prices = $this->promoCodeService->checkValidity(CheckValidityPromoCodeDTOData::from($request->validated()));
        return  response()->json([$prices]);
    }
}
