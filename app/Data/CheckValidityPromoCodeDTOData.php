<?php

namespace App\Data;

use Spatie\LaravelData\Dto;

class CheckValidityPromoCodeDTOData extends Dto
{
    public function __construct(
        public string $promo_code,
        public float $price,
    ) {}
}
