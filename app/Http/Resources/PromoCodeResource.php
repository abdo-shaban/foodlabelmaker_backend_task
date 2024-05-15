<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                 => $this->id,
            "code"               => $this->code,
            "expiry_date"        => $this->expiry_date,
            "max_usage_count"    => $this->max_usage_count,
            "max_usage_per_user" => $this->max_usage_per_user,
            "user_ids"           => $this->user_ids,
            "type"               => $this->type,
            "value"              => $this->value,
            "created_at"         => $this->created_at,
            "updated_at"         => $this->updated_at,
        ];
    }
}
