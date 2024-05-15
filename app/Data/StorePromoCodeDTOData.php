<?php

namespace App\Data;

use Spatie\LaravelData\Dto;

class StorePromoCodeDTOData extends Dto
{
    public function __construct(
        public ?string $code,
        public ?string $expiry_date,
        public ?int $max_usage_count,
        public ?int $max_usage_per_user,
        public ?array $user_ids,
        public string $type,
        public float $value,
    ) {}
}
